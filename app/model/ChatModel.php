<?php

use Dibi\DataSource;

class ChatModel extends AbstractModel {

    public function find($id) {
        $this->checkEmptiness($id, "id");
        return $this->findAll()->where("[post_id_chat] = %i", $id)->fetch();
    }

    /**
     * @return DataSource
     */
    public function findAll() {
        return $this->getConnection()->dataSource("SELECT * FROM [view_chat]");
    }
    /**
     * @return DataSource
     */
    public function findAllRoot() {
        return $this->getConnection()->dataSource('SELECT * FROM [view_chat_root]')->orderBy('last_post_inserted','DESC');
    }

    public function insert($team, $content, $parentPost) {
        if($parentPost === "null" || !is_int(intval($parentPost))) {
            $parentPost = null;
        }
        $this->checkEmptiness($team, "team");
        $this->checkEmptiness($content, "content");
        $return = $this->getConnection()->insert("chat", array(
                "id_team"    => $team,
                "content"    => $content,
                "id_parent" => $parentPost,
                "inserted"    => new DateTime()
                ))->execute();
        $this->log($team, "chat_inserted", "The team successfuly contributed to the chat.");
        return $return;
    }
}
