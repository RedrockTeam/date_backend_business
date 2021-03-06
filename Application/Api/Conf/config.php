<?php
return array(
	//'配置项'=>'配置值'
    'DEFAULT_MODULE'       =>    'Api',  // 默认模块
    'DEFAULT_FILTER'        => 'htmlspecialchars,strip_tags,trim',//自定义过滤方法
    /**
     * 路由配置
     */
    'URL_ROUTER_ON'   => true,
    'URL_ROUTE_RULES' => [
        //公共
        'public/banner'   => 'Public/banner',//广告位
        'public/login'    => 'Account/login',//登录
        'public/register' => 'Account/register',
        'public/school'   => 'Public/school',//学校
        'public/datetype' => 'Public/type', //约类型
        'test/delAccount' => 'Account/telDelete',
        'test/test'       => 'Account/test',

        'account/verify'  => 'Account/realNameVerify',//实名认证
        'account/pwdfind' => 'Account/passwordFind',//密码找回

        //用户接口
        'user/info'           => 'Users/info', //获取个人信息
        'user/collection'     => 'Collection/collection', //获取收藏列表
        'user/getletter'      => 'Test/getletter', //获取私信 todo
        'user/postletter'     => 'Test/postletter', //发出私信 todo
        'user/mydatelist'     => 'Users/createdDate', //获取发起的约
        'user/joineddatelist' => 'Users/joinedDate', //获取参加的约
        'user/care'           => 'Users/care', //我关注的人
        'user/careme'         => 'Users/caremMe', //关注我的人
        'user/editavatar'     => 'Users/editAvatar', //修改头像
        'user/editsignature'  => 'Users/editSignature', //修改个性签名
        'user/edithobby'      => 'Users/editHobby', //修改爱好
        'user/editpassword'   => 'Users/editPassword', //修改密码
        'user/carenum'        => 'Users/careNum', //获取关注/被关注数
        'user/addcare'        => 'Users/addCare', //关注某人
        'user/delcare'        => 'Users/delCare', //取关某人
        'user/search'         => 'Users/search', //搜索用户
        'user/hotsearch'      => 'Users/hotsearch', //热搜用户

        //消息接口

        'message/chattoken'  => 'Message/getToken', //获取聊天室Token
        'message/msggroup'   => 'Message/messageGroup', //获取所有消息
        'message/msgone'     => 'Message/messageOne', //获取单条消息


        //约会接口
        'date/datelist'      => 'Homepage/dateGroup', //获取约列表
        'date/datefilter'    => 'Homepage/filter',  //条件筛选
        'date/detaildate'    => 'DatePublic/detailDate', //获取约详情
        'date/createdate'    => 'Date/createDate', //发布约
        'date/judgedate'     => 'Test/replyDate', //接受/拒绝约
        'date/applydate'     => 'Date/applyDate', //报名约
        'date/collectdate'   => 'Date/collectDate', //收藏约
        'date/commentdate'   => 'Date/commentDate', //评论
        'date/datepraise'    => 'Date/praiseDate', //点赞约
        'date/deldatepraise' => 'Date/delPraiseDate', //取消点赞约
        'date/search'        => 'DatePublic/search', //搜索约
        'date/hotsearch'     => 'DatePublic/hotsearch', //热搜关键词
        'date/comment'       => 'DatePublic/getComment', //获取评论

        //发现接口
        'discover/discoverlist'      => 'Discover/discoverList', //获取发现列表
        'discover/detaildiscover'    => 'Discover/detaildDiscover', //发现详情
        'discover/applydiscover'     => 'Discover/applyDiscover', //参加活动
        'discover/creatediscover'    => 'Discover/createDiscover', //发布活动
        'discover/commentdiscover'   => 'Discover/commentDiscover', //评论发现
        'discover/discoverpraise'    => 'Discover/praiseDiscover', //评论发现
        'discover/deldiscoverpraise' => 'Discover/delPraiseDiscover', //评论发现
        'discover/search'            => 'discover/search', //搜索
        'discover/comment'       => 'discover/getComment', //获取评论

    ]
  );
