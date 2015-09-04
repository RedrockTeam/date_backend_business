<?php

namespace Api\Model;
use Think\Model;

class DiscoverCommentModel extends Model {

    protected $tableName  = 'discover_comment';

    public function getComment($input) {
        $page = $input['page']>0?$input['page']:1;
        $limit = 20;
        $offset = ($page-1)*$limit;
        $map = [
            'discover_id' => $input['discover_id']
        ];
        $data = $this->where($map)
            ->join('join users on discover_comment.user_id = users.id')
            ->limit($offset, $limit)
            ->field('discover_comment.user_id as uid, users.avatar, users.nickname, discover_comment.id as comment_id, discover_comment.content, discover_comment.time')
            ->select();
        foreach($data as &$value) {
            $value['reply'] = $this->where(array('father_id' => $value['comment_id']))
                ->join('join users on discover_comment.user_id = users.id')
                ->limit($offset, $limit)
                ->field('discover_comment.user_id as uid, users.avatar, users.nickname,  discover_comment.content, discover_comment.time')
                ->select();

        }
        return $data;
    }
}