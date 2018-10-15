//app.js
App({
  onLaunch: function () {
    // 登录
    wx.login({
      success: function(res) {
        // 发送 res.code 到后台换取 openId, sessionKey, unionId
        if(res.code){
          wx.request({
            url: 'http://wechat.homedoctor.jianfengweb.com/WechatUser/login_do',
            data:{
              code:res.code
            },
            success:function(res){
              if(!res.data.data.username || res.data.data.username == " "){
                wx.navigateTo({
                  url:'/pages/login/login',
                })
              }
              console.log(res.data.data)
              wx.setStorageSync('userid', res.data.data.userid)
              wx.setStorageSync('username', res.data.data.username)
              wx.setStorageSync('userimage', res.data.data.userimage)
              wx.setStorageSync('is_doctor', res.data.data.is_doctor)
            }
          })
        }else{
          console.log('获取用户登录态失败！' + res.errMsg)
        }

      }
    })
    //非首次登陆 获取username
    var username = wx.getStorageSync('username');
    if (username == " " && userid != " ") {
      wx.navigateTo({
        url: '/pages/login/login',
      })
    }
  },
  globalData: {
    userInfo: null
  }
})