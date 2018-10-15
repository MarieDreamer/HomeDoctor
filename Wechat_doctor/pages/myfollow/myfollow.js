// pages/myfollow/myfollow.js
Page({

  /**
   * 页面的初始数据
   */
  data: {
  
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    wx.request({
      url: 'http://wechat.homedoctor.jianfengweb.com/WechatUser/getFollow',
      data:{
        user_id:wx.getStorageSync('userid'),
      },
      success: res => {
        this.setData({
          follow:res.data.data
        })
      }
    })
  },
  person_detail:function(e){
    var user_id = e.currentTarget.dataset.id;
    var introduce = e.currentTarget.dataset.introduce;
    wx.navigateTo({
      url: '../person_detail/person_detail?user_id=' + user_id +'&introduce=' + introduce,
    })
  },

})