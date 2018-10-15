// pages/patient_case/patient_case.js
Page({
  /**
   * 页面的初始数据
   */
  data: {
    a:"",
    history:"",
    personal:"",
    orderinfo: [],
    jiedan:[],
    age:"",
    name:"",
    sex:"",
    addres:"",
    disease:"",
  },
  submit: function (e) {
    var userid = wx.getStorageSync('userid');
    // console.log(e.currentTarget.dataset.st);
    // console.log(e.currentTarget.dataset.user);
    var that = this;
    wx.request({
      url: 'http://wechat.homedoctor.jianfengweb.com/Order/receipt_do',
      data: {
        id:e.currentTarget.dataset.st,
        userid: userid,
      },
      success: function (res) {
        // console.log(res.data.status)
        var status=res.data.status;
        if(status==0){
          wx.showToast({
            title: '操作失败',
            duration: 2000,
            icon:"none"
          })
        }
        else if(stuatus==1){
          wx.showToast({
            title: '接单成功',
            duration: 2000
          })
        }
      },
    })
  },
  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (e) {
    // var  id3= wx.getStorageSync('patientid');
    var that = this;
    wx.request({
      url: 'http://wechat.homedoctor.jianfengweb.com/Order/listsfind',
      data: {
        id:e.id,
      },
      success: function (res) {
        // console.log(res);
        var name=res.data.lists.name;
        var age=res.data.lists.age;
        var sex = res.data.lists.sex;
        var phone=res.data.lists.phone;
        var addres=res.data.lists.addres;
        var disease=res.data.lists.disease;
        that.setData({
          name:name,
          age:age,
          sex:sex,
          phone:phone,
          addres:addres,
          disease:disease,
        })
      },
    })
    wx.request({
      url: 'http://wechat.homedoctor.jianfengweb.com/Medicalrecord/resume_lists',
      data: {
        patientid:14,
      },
      success: function (res) {
        console.log(res);
        that.setData({
          history:res.data.lists['history'],
          personal:res.data.lists['personal']
        })
      },
      
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