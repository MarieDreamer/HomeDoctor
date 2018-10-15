// var e = getApp(), t = require("../../js/global.js");

Page({
    data: {
        url: "/pages/index/index",
        userInfo: {
            nickname: "",
            sex: "",
            head_pic: ""
        },
        text: "微信授权登录"
    },
    agreeGetUser: function (e) {
        var that = this;
        var msg = e.detail.errMsg;
        if (msg == 'getUserInfo:fail auth deny') {
            console.log('用户不允许授权')
            wx.navigateTo({
            url: '/pages/login/login',
            })
        }
        if (msg == 'getUserInfo:ok') {
            console.log('用户允许授权')
            wx.switchTab({
                url: '/pages/index/index',
                fail:function(e){
                  console.log(e)
                }
            })
        }
        //授权保存用户信息
        var userid = wx.getStorageSync('userid');
        var username = wx.getStorageSync('username');
        if(!username){
            wx.request({
              url: 'http://wechat.homedoctor.jianfengweb.com/WechatUser/save_do',
                data: {
                    id: userid,
                    nickname: e.detail.userInfo.nickName,
                    imageurl: e.detail.userInfo.avatarUrl,
                    gender: e.detail.userInfo.gender,
                    province: e.detail.userInfo.province,
                    city: e.detail.userInfo.city,
                    country: e.detail.userInfo.country,
                },
                success: function (res) {
                    if (e.detail.userInfo.nickName){
                        wx.setStorageSync('username', e.detail.userInfo.nickName);
                    }
                }
            })
        }
      
    },
   
    onLoad: function(t) {
    }
});