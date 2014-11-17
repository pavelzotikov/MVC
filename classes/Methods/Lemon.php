<?php defined('DOCROOT') or die('No direct script access.');

class Methods_Lemon
{

    protected $backend;
    protected $flags;

    public static function instance()
    {
        return Methods_Instance::getInstance()->get('lemon');
    }

    public function __construct()
    {
        $this->backend = new Memcache();
        $this->flags = FALSE;

        $this->backend->addServer('127.0.0.1', 11211, FALSE);
    }

    public function get($id)
    {
        $value = $this->backend->get($id);

        if ($value === FALSE) return NULL;

        if (!empty($value['tags']) && count($value['tags']) > 0) {
            $expired = false;

            foreach ($value['tags'] as $tag => $tag_stored_value) {
                $tag_current_value = $this->get_tag_value($tag);

                if ($tag_current_value != $tag_stored_value) {
                    $expired = true;
                    break;
                }
            }

            if ($expired) return NULL;
        }

        return $value['data'];
    }

    public function delete_tag($tag)
    {
        $key = "tag_" . $tag;
        $this->get_tag_value($tag);

        $this->set($key, microtime(true), NULL, 60 * 60 * 24 * 30);

        return true;
    }

    private function get_tag_value($tag)
    {
        $key = "tag_$tag";

        $tag_value = $this->get($key);

        if ($tag_value === NULL) {
            $tag_value = microtime(true);
            $this->set($key, $tag_value, NULL, 60 * 60 * 24 * 30);
        }

        return $tag_value;
    }

    public function set($id, $data, array $tags = NULL, $lifetime)
    {
        if (!empty($tags)) {
            $key_tags = array();

            foreach ($tags as $tag) {
                $key_tags[$tag] = $this->get_tag_value($tag);
            }

            $key['tags'] = $key_tags;
        }

        $key['data'] = $data;

        if ($lifetime !== 0) {
            $lifetime += time();
        }

        return $this->backend->set($id, $key, $this->flags, $lifetime);
    }

    public function delete($id)
    {
        if ($id == TRUE) return $this->backend->flush();
        return $this->backend->delete($id);
    }

}