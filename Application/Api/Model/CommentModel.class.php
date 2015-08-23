<?php

namespace Api\Model;
use Think\Model;

class CommentModel extends Model {

    protected $tableName  = 'comment';

    public function getComment($input) {
        $page = $input['page']>0?$input['page']:1;
        $limit = 10;
        $offset = ($page-1)*$limit;
        $map = [
            'date_id' => $input['date_id']
        ];
        $data = $this->where($map)
                    ->join('join users on comment.user_id = users.id')
                    ->limit($offset, $limit)
                    ->field('comment.user_id as uid, users.avatar, users.nickname, comment.id as comment_id, comment.content, comment.time')
                    ->select();
        foreach($data as &$value) {
            $value['reply'] = $this->where(array('father_id' => $value['comment_id']))
                                  ->join('join users on comment.user_id = users.id')
                                  ->limit($offset, $limit)
                                  ->field('comment.user_id as uid, users.avatar, users.nickname,  comment.content, comment.time')
                                  ->select();

        }
        return $data;
    }

}