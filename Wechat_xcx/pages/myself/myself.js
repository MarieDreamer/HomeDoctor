//index.js
//获取应用实例
const app = getApp()

Page({
  data: {
    motto: 'Hello World',
    userInfo: {},
    hasUserInfo: false,
    canIUse: wx.canIUse('button.open-type.getUserInfo')
  },
  //事件处理函数
  bindViewTap: function() {
    wx.navigateTo({
      url: '../logs/logs'
    })
  },
  invite:function(res){
      wx.navigateTo({
        url: '/pages/invite/invite',
      })
  },
  onLoad: function () {
    this.setData({
      userimage: wx.getStorageSync('userimage'),
      username: wx.getStorageSync('username')
    })

    if (app.globalData.userInfo) {
      this.setData({
        userInfo: app.globalData.userInfo,
        hasUserInfo: true
      })
    } else if (this.data.canIUse){
      // 由于 getUserInfo 是网络请求，可能会在 Page.onLoad 之后才返回
      // 所以此处加入 callback 以防止这种情况
      app.userInfoReadyCallback = res => {
        this.setData({
          userInfo: res.userInfo,
          hasUserInfo: true
        })
      }
    } else {
      // 在没有 open-type=getUserInfo 版本的兼容处理
      wx.getUserInfo({
        success: res => {
          app.globalData.userInfo = res.userInfo
          this.setData({
            userInfo: res.userInfo,
            hasUserInfo: true
          })
        }
      })
    }
  },
  getUserInfo: function(e) {
    console.log(e)
    app.globalData.userInfo = e.detail.userInfo
    this.setData({
      userInfo: e.detail.userInfo,
      hasUserInfo: true
    })
  },
  onShareAppMessage: function () {
    return {
      title: '家庭医生',
      path: '/page/user?id=123',
      success: function (res) {
        wx.request({
          url: 'http://wechat.homedoctor.com/WechatUser/share_do',
          data:{
            user_id:wx.getStorageSync('userid')
          },
          success:function(res){
            console.log('分享成功')
          }
        })
      },
    }
  },

  //管理地址跳转
  goaddress: function (e) {
    wx.navigateTo({
      url: '/pages/address/address',
    })
  },

  //我的医生页面跳转
  gomydoctor: function (e) {
    wx.navigateTo({
      url: '/pages/mydoctor/mydoctor',
    })
  },

  //我的订单跳转
  gomyorder: function (e) {
    wx.navigateTo({
      url: '/pages/myorder/myorder',
    })
  },

  //我的动态
  gomydynamic: function (e) {
    wx.navigateTo({
      url: '/pages/my_moments/my_moments',
    })
  },

  //我的关注
  gomyfollow: function (e) {
    wx.navigateTo({
      url: '/pages/myfollow/myfollow',
    })
  },

  //关于页面
  goabout: function (e) {
    wx.navigateTo({
      url: '/pages/about/about',
    })
  },

  //病历
  gomedicalrecord: function (e) {
    wx.navigateTo({
      url: '/pages/medicalrecord/medicalrecord',
    })
  },
  gopersonalinfo:function(e){
    wx.navigateTo({
      url: '/pages/personalinfo/personalinfo',
    })
  }
})
