// pages/invite/invite.js
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
  
  },
  formSubmit:function(e){
    var that=this;
    var user_id = wx.getStorageSync('userid');
    var invite_code = e.detail.value.invite_code;
    console.log(e)
    wx.request({
      url: 'http://wechat.homedoctor.com/Doctor/weixin_bind',
      data:{
        user_id:user_id,
        invite_code:invite_code
      },
      success:function(res){
        wx.showToast({
          title: '绑定成功',
          icon: 'success',
          mask: true,
        })
        wx.navigateTo({
          url: '/pages/index/index',
        })
      }
    })
  },
  /**
   * 生命周期函数--监听页面初次渲染完成
   */
  onReady: function () {
  
  },

  /**
   * 生命周期函数--监听页面显示
   */
  onShow: function () {
  
  },

  /**
   * 生命周期函数--监听页面隐藏
   */
  onHide: function () {
  
  },

  /**
   * 生命周期函数--监听页面卸载
   */
  onUnload: function () {
  
  },

  /**
   * 页面相关事件处理函数--监听用户下拉动作
   */
  onPullDownRefresh: function () {
  
  },

  /**
   * 页面上拉触底事件的处理函数
   */
  onReachBottom: function () {
  
  },

  /**
   * 用户点击右上角分享
   */
  onShareAppMessage: function () {
  
  }
})