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
    - 1    请求参数错误
    - 1001    服务器繁忙，通常是后台出错
    - 1002    用户没有登录态
    - 1003    账户被冻结
    - -1    请求参数无效
    - -3    无API访问权限
    
4. 约本身状态说明
    
    - 0 结束
    - 1 成功
    - 2 报名中
    
5. 	用户对于约状态说明
	
	- 0 被拒绝
	- 1 被接受
	- 2 已报名
	- 3 未报名
	
6. 用户角色说明

	- 1 未认证普通用户
	- 2 已认证普通用户
	- 3 商家(所有商家均已认证)
    
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

        - URL: api/public/datetype
        Request: 
                {
                    type_id : ,//为空返回所有导航, type_id为一级导航返回所属二级导航
                }
        Response:
                {
                    status: 0,
                    info: '成功',
                    data: 
                    [
                        {
                            type_id:1
                            type_name:运动,
                            type_son: [
                                        {type_id:6, type_name:篮球},
                                        {.....}
                                        ]
                        },
                        {.....}
                        
                    ]
                }
        
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
                        {
                        date_id: ,
                        uid: ,
                        avatar: ,
                        title: ,
                        date_time: ,
                        create_time: ,
                        date_status: 
                        },
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
    - 用户关注数
    
            URL: api/user/carenum
            Request: 
            {
                uid:,
                token:,
                get_uid: 
            }
            Response: 
            {
                status:0,
                info:"成功",
                data:[
                        care_num: ,//我关注的
                        cared_num: ,//关注我的
                ]
            }

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
                        {
                        uid: ,
                        nickname: ,
                        avatar: ,
                        signature: ,
                        charm: ,
                        role_id: 
                        },
                        {....}
                ]
            }

6. 关注/取关接口

        -关注
        
            URL: api/user/addcare
            Request: 
            {
                uid:,
                token:,
                add_uid: 
            }
            Response: 
            {
                status:0,
                info:"成功"
            }
                        
        - 取关
        
            URL: api/user/delcare
            Request: 
            {
                uid:,
                token:,
                del_uid: 
            }
            Response: 
            {
                status:0,
                info:"成功"
            }

7. 实名认证
        
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


8. 个人信息修改

    - 修改头像
            
            URL: api/user/editavatar
            Request: 
            {
                uid:,
                token:,
                avatar: ,//七牛url
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
                signature: ,
            }
            Response: 
            {
                status:0,
                info:"成功",
            }

    
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
                page: ,
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
                        cost_type: ,//话费类型
                        date_place: , //约会地点
                        praise: ,//点赞
                        comment_num: ,//评论数量
                        apply_num: ,//报名人数
                        uid: ,
                        nickname: ,
                        signature: ,
                        avatar: ,
                        gender: ,
                        grade: ,//int
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
                date_id:
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
                        apply_num: ,//报名人数
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
                        date_status: ,//0结束, 1成功, 2受理中
                        user_date_status: ,//用户状态, 0被拒绝, 1被接受, 2已报名
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
    
    - URL: api/date/commentdate
    
                  Request: 
                  {
                      uid: ,
                      token: ,
                      date_id: ,
                      father_id: ,//默认为0, 对楼层进行回复时带上当前 **层主** comment_id, 类似贴吧楼中楼实现
                  }
                  Response: 
                  {
                      status:0,
                      info:"成功",    
                  }

##发现接口
1. 获取发现列表
     
        URL: api/discover/discoverlist
            Request: 
            {
                uid: ,
                token: ,
                page: ,
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
                     discover_picture: , //图片
                     discover_time: , //截止时间
                     discover_praise: , //点赞数
                     praise_status: , //用户是否点赞, 1是0否
                     discover_status: //发现状态, 0 结束 1 进行中
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
                discover_id:
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

5. 发布发现(仅商家)

         URL: api/discover/creatediscover
                    Request: 
                    {
                        uid: ,
                        token: ,
                        title: ,
                        caption: ,//副标题
                        picture: ,//图片地址
                        place: ,//地点
                        time: ,//开始时间
                    }
                    Response: 
                    {
                        status:0,
                        info:"成功",    
                    } 
        
                
