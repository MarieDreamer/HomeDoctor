// pages/shangmen/shangmen.js
Page({

  /**
   * 页面的初始数据
   */
  data: {
    doctorarray:[],

  
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    let that = this;
    wx.request({
      url: 'http://wechat.homedoctor.jianfengweb.com/Doctor/lists',
      data: {
      },
      success: function (res) {
        // console.log(res.data.lists);
        var doctorarray = res.data.lists;
        // console.log(sonarray);
        that.setData({
          doctorarray: doctorarray
        })

      }
    })
  },

  goorderfun: function (e) {
    var that = this;
    // console.log(e.currentTarget.dataset.doctorid);
    var doctorid = e.currentTarget.dataset.doctorid;
    wx.navigateTo({
      url: "/pages/order/order?doctorid=" + doctorid,
    });
  }, 

  goofastrderfun: function (e) {
    var that = this;
    // console.log(e.currentTarget.dataset.disease);
    var disease = e.currentTarget.dataset.disease;
    wx.navigateTo({
      url: "/pages/order/order?disease=" + disease,
    });
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