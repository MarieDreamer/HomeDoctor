// pages/querydisease/querydisease.js
Page({

  /**
   * 页面的初始数据
   */
  data: {
    listsarray:[],
    name:"",
    letterchoice:"",
    position:"",
  
  },
  

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
  
  },

  SearchInput: function (e) {
    var that = this;
    // console.log(e.detail.value);
    var name = e.detail.value;
    // console.log(name);
    
    wx.request({
      url: 'http://wechat.homedoctor.jianfengweb.com/Disease/lists?name=' + name,
      data: {
      },
      success: function (res) {
        console.log(res.data.lists);
        if (res.data.lists){
          var listsarray = res.data.lists;
          that.setData({
            listsarray: listsarray,
          })
        }
        

      }
    })
  }, 

  godiseasedetailed: function (e) {
    // console.log(e.currentTarget.dataset.id);
    var diseaseid = e.currentTarget.dataset.id;
    // console.log(diseaseid);
    wx.navigateTo({
      url: "/pages/diseasedetailed/diseasedetailed?diseaseid=" + diseaseid,
    });
  },

  godiseaselookup: function (e) {
    console.log(e.currentTarget.dataset.id);
    var letterchoice = e.currentTarget.dataset.id;
    // console.log(diseaseid);
    wx.navigateTo({
      url: "/pages/diseaselookup/diseaselookup?letter=" + letterchoice,
    });
  },

  positiongofun: function (e) {
    console.log(e);
    var position = e.currentTarget.dataset.id;
    // // console.log(diseaseid);
    wx.navigateTo({
      url: "/pages/diseaselookup/diseaselookup?bodydisease=" + position,
    });
  },

  departmentgofun: function (e) {
    console.log(e.currentTarget.dataset.id);
    var department = e.currentTarget.dataset.id;
    // console.log(diseaseid);
    wx.navigateTo({
      url: "/pages/diseaselookup/diseaselookup?department=" + department,
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