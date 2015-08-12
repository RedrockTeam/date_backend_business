#约创业版接口文档

##特别强调
###<p style=“color:red;">本文档为预设文档，Request以及Response可能随时发生变动，请留意</p>

##说明
1. 所有接口请求方式均为POST, 除公共接口外访问均需uid及token
2. 返回格式为

			{
				status:0,
				info:"成功",
				data:[]
			}
			
3. 错误码说明
	- 0 正确返回
	- 1	请求参数错误
	- 1001	服务器繁忙，通常是后台出错
	- 1002	用户没有登录态
	- 1003	账户被冻结
	- -1	请求参数无效
	- -3	无API访问权限
	
	
##公共接口

1. 获取Banner
		
			URL: api/public/banner
			Request: null
			Response: 
			{
				status:0,
				info:"成功",
				data:[
							{"href": 'http://www.baidu.com', "img": ""},
							{"href": 'http://www.baidu.com', "img": ""},
							{"href": 'http://www.baidu.com', "img": ""},
				]
			}
			
			
2. 登录
			
			URL: api/public/login/
			Request: {
						username:"",
						password:""
			}
			Response: 
			{
				status:0,
				info:"成功",
				data:[
						uid:1, //用户id
						avatar:"http://dsfsdaa", //用户头像
						nickname:"刘慧芝减肥成功了吗?", //用户昵称
						signature:"23333333", //用户个性签名
						gender:1, //用户性别1男2女
						role_id:1, //用户身份
						token:'asdfghjkl', //token
						
				]
			}
			
3. 约会类型列表

		- 待定
		
4. 学校

			URL: api/public/school
			Request: null 
			Response: 
			{
				status:0,
				info:"成功",
				data:[
						{
						school_id:1,
						school_name:"重邮"
						},
						{
						school_id:2,
						school_name:"重大"
						},
						{
						school_id:3,
						school_name:"家里蹲大"
						}
				]
			}
			
##用户接口

1. 获取个人信息
 
 			URL: api/user/info
			Request: 
			{
				uid:,
				token:,
				get_uid:,//需要获取的人的uid
			}
			Response: 
			{
				status:0,
				info:"成功",
				data:[
						uid: ,
						nickname: ,
						avatar: ,
						realname: ,//真名, 获取自己信息才会出现
						gender: ,
						school: ,
						signature: ,
						hobby: [
							"吃",
							"喝",
							"嫖",
							"赌"
						],
						role_id: ,//身份
						charm: //魅力值
				]
			}

2. 获取收藏列表

		URL: api/user/collection
		Request: 
			{
				uid:,
				token:,
				page:,
			}
		Response: 
			{
				status:0,
				info:"成功",
				data:[
						{
							date_id: ,
							title: ,
							uid: ,
							avatar: ,
							nickname: ,
							date_status: 
						},
						{.....}
				]
			}
		Attention: 查看收藏约详情直接根据date_id请求约详情接口


3. 获取私信

	- 收到
	 
 			URL: api/user/getletter
			Request: 
			{
				uid: ,
				token: ,
				page: 
			}
			Response: 
			{
				status:0,
				info:"成功",
				data:[
						from: ,//发信人
						from_avatar: ,
						to: ,//收信人
						to_avatar: ,
						content: ,
						time: ,
				]
			}
	- 发出
 
 			URL: api/user/postletter
			Request: 
			{
				uid:,
				token:,
				page: 
			}
			Response: 
			{
				status:0,
				info:"成功",
				data:[
						from: ,
						from_avatar: ,
						to: ,
						to_avatar: ,
						content: ,
						time: ,
				]
			}
			
4. 获取约会记录

	- 获取发起的约
	 
 			URL: api/user/mydatelist
			Request: 
			{
				uid:,
				token:,
				page: 
			}
			Response: 
			{
				status:0,
				info:"成功",
				data:[
						{date_id: ,
						uid: ,
						avatar: ,
						title: ,
						date_time: ,
						create_time: ,
						date_status: },
						{.....}
				]
			}

	
	
	- 获取参加的约
 
 			URL: api/user/joineddatelist
			Request: 
			{
				uid:,
				token:,
				page: 
			}
			Response: 
			{
				status:0,
				info:"成功",
				data:[
						{date_id: ,
						uid: ,
						avatar: ,
						title: ,
						time: ,
						date_status: 
						},
						{....}
				]
			}


	
5. 关注列表

	- 我关注的人
	 		
	 		URL: api/user/care
			Request: 
			{
				uid:,
				token:,
				page: 
			}
			Response: 
			{
				status:0,
				info:"成功",
				data:[
						{
						uid: ,
						nickname: ,
						avatar: ,
						signature: ,
						charm: ,
						role_id:
						},
						{...}
				]
			}

	
	- 关注我的人 
	
		 	URL: api/user/careme
			Request: 
			{
				uid:,
				token:,
				page: 
			}
			Response: 
			{
				status:0,
				info:"成功",
				data:[
						uid: ,
						nickname: ,
						avatar: ,
						signature: ,
						charm: ,
						role_id: 
				]
			}



6. 实名认证
    	
		 	URL: api/user/userverify
			Request: 
			{
				uid:,
				token:,
				studentidentity: file,
			}
			Response: 
			{
				status:0,
				info:"成功",
			}
        Attention: form-data


7. 个人信息修改

	- 修改头像
			
			URL: api/user/editavatar
			Request: 
			{
				uid:,
				token:,
				avatar: file,
			}
			Response: 
			{
				status:0,
				info:"成功",
			}
			Attention: form-data

	
	- 修改个性签名
	   
	    	URL: api/user/editsignature
			Request: 
			{
				uid:,
				token:,
				avatar: file,
			}
			Response: 
			{
				status:0,
				info:"成功",
			}
			Attention: form-data

	
	- 爱好
		   
	    	URL: api/user/edithobby
			Request: 
			{
				uid:,
				token:,
				hobby: [1,2,3,4] //hobby id
			}
			Response: 
			{
				status:0,
				info:"成功"
			}

	
	- 密码
	
            URL: api/user/editpassword
			Request: 
			{
				uid:,
				token:,
				oldpassword: ,
				newpassword: ,
			}
			Response: 
			{
				status:0,
				info:"成功"
			}

	

##约会接口

1. 获取约列表
 	
        URL: api/date/datelist
			Request: 
			{
				uid:,
				token:,
			}
			Response: 
			{
				status:0,
				info:"成功",
				data:[
				    {
				        date_id: ,
				        title: ,
				        content: , //详情
				        created_time: ,//发布时间
				        date_type: ,//约会类型
				        praise: ,//点赞
				        comment_num: ,//评论数量
				        apply_num: ,//报名人数
				        uid: ,
				        nickname: ,
				        signature: ,
				        avatar: ,
				        gender: ,
				        role_id: ,
				    },
				    {
				    .......
				    }
				]
			}


2. 获取约详情
 	
        URL: api/date/detaildate
			Request: 
			{
				uid:,
				token:,
			}
			Response: 
			{
				status:0,
				info:"成功",
				data:[
				    
				        date_id: ,
				        title: ,
				        content: , //详情
				        created_time: ,//发布时间
				        date_type: ,//约会类型
				        date_place: ,//约会地点
				        date_time: ,//约会时间
				        cost_type: ,//消费方式
				        people_limit: ,//人数限制
				        gender_limit: ,
				        school_limit: [{school_id:1,school_name:"重邮"}],
				        date_comment:[
				        {
				            uid: ,
				            avatar: ,
				            nickname: ,
				            comment_id: ,
				            content: ,
				            time: ,
				            reply: [
				            {
				               uid: ,
				               avatar: ,
				               nickname: ,
				               content: ,
				               time: ,
				                },
				            ]
				        }
				        ],
				        date_status: ,
				        uid: ,
				        avatar: ,
				        gender: ,
				        role_id: ,
				    			
				    ]
			}


3. 发布约

 	
        URL: api/date/createdate
			Request: 
			{
				uid: ,
				token: ,
				title: ,
				content: ,
				date_time: ,
				date_place: ,
				date_type: ,
				cost_type: ,
				gender_limit: ,
				shcool_limit: [1,2,3,4]
			}
			Response: 
			{
				status:0,
				info:"成功",	
			}




4. 接受/拒绝约
 	
        URL: api/date/judgedate
			Request: 
			{
				uid: ,
				token: ,
				request_uid: ,//发出请求用户的id
				date_id: ,
				operation: ,//0拒绝, 1接受
			}
			Response: 
			{
				status:0,
				info:"成功",	
			}

5. 报名约 	
 
        URL: api/date/applydate
			Request: 
			{
				uid: ,
				token: ,
				date_id: 
			}
			Response: 
			{
				status:0,
				info:"成功",	
			}

6. 收藏约
  	
        URL: api/date/collectdate
			Request: 
			{
				uid: ,
				token: ,
				date_id: 
			}
			Response: 
			{
				status:0,
				info:"成功",	
			}

7. 评论
    
    - ...

##发现接口
1. 获取发现列表
 	
        URL: api/discover/discoverlist
			Request: 
			{
				uid: ,
				token: ,
			}
			Response: 
			{
				status:0,
				info:"成功",	
				data: 
				[
				    {
				     discover_id: ,
				     discover_title: ,
				     discover_caption: , //副标题
				     discover_picture: ,//图片
				    },
				    {.......}    
				]
			}


2. 获取发现详情

 	
        URL: api/discover/detaildiscover
			Request: 
			{
				uid: ,
				token: ,
			}
			Response: 
			{
				status:0,
				info:"成功",	
				data: 
				[
				     discover_id: ,
				     discover_title: ,
				     discover_caption: , //副标题
				     discover_picture: ,//图片 
				     discover_time: ,//活动时间
				     discovet_place: ,//活动地点
				     discover_praise: ,//点赞数
				     discover_status: ,//发现状态
				     discover_comment: [
				         {
				            uid: ,
				            avatar: ,
				            nickname: ,
				            comment_id: ,
				            content: ,
				            time: ,
				            reply: [
				            {
				               uid: ,
				               avatar: ,
				               nickname: ,
				               content: ,
				               time: ,
				                },
				            ]
				        }
                    ] 
				]
			}




3. 参加活动

        URL: api/discover/applydiscover
			Request: 
			{
				uid: ,
				token: ,
				discover_id: 
			}
			Response: 
			{
				status:0,
				info:"成功",	
			}



4. 搜索

    - ...
		
				
