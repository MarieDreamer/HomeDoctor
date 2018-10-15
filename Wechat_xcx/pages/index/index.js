//index.js
//获取应用实例
const app = getApp()

Page({
  data: {
  },
  //事件处理函数
  examinefun: function () {
    wx.navigateTo({
      url : "/pages/querydisease/querydisease",
    });
  },
  bindViewTap: function() {
  },
  onLoad: function () {
  },
  getUserInfo: function() {
  },
  shangmen: function () {
    wx.navigateTo({
      url: "/pages/shangmen/shangmen",
    });
  },
  kuaisu: function () {
    wx.navigateTo({
      url: "/pages/kuaisu/kuaisu",
    });
  },
  zhongyi: function () {
    // wx.navigateTo({
    //   url: "/pages/zhongyi/zhongyi",
    // });
    wx.showToast({
      icon: 'none',
      title: "正在开发中",
      duration: 500,
    })
  },

  songyi: function () {
    // wx.navigateTo({
    //   url: "/pages/zhongyi/zhongyi",
    // });
    wx.showToast({
      icon: 'none',
      title: "正在开发中",
      duration: 500,
    })
  },
})
