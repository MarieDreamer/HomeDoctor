// pages/diseasedetailed/diseasedetailed.js
Page({

  /**
   * 页面的初始数据
   */
  data: {
    diseasearray:[],
    name:"",
    diseasecontent:"",
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    var that = this;
    console.log(options.diseaseid);
    if (options.diseaseid){
      var diseaseid = options.diseaseid;
      //通过ID查询疾病传回数组
      wx.request({
        url: 'http://wechat.homedoctor.jianfengweb.com/Disease/lists?id=' + diseaseid,
        data: {
        },
        success: function (res) {
          if (res.data.lists){
            var name = res.data.lists[0].name;
            var diseasearray = res.data.lists[0];
            var diseasecontent = res.data.lists[0].summarize;
            that.setData({
              name: name,
              diseasearray: diseasearray,
              diseasecontent: diseasecontent,
            })
          }
          // console.log(res.data.lists[0]);
          // if (res.data.lists) {
          //   var listsarray = res.data.lists;
          //   that.setData({
          //     listsarray: listsarray,
          //   })
          // }

        }
      })
    }
  
  },

  contentfun: function (e) {
    var that = this;
    // console.log(e.currentTarget.dataset.id);
    if (e.currentTarget.dataset.id =='summarize'){
      var diseasecontent = that.data.diseasearray.summarize
      that.setData({
        diseasecontent: diseasecontent,
      })
    }

    if (e.currentTarget.dataset.id == 'pathogenesis') {
      var diseasecontent = that.data.diseasearray.pathogenesis
      if (!diseasecontent) {
        diseasecontent = "病因：暂无数据";
      }
      that.setData({
        diseasecontent: diseasecontent,
      })
    }

    if (e.currentTarget.dataset.id == 'symptoms') {
      var diseasecontent = that.data.diseasearray.symptoms
      if (!diseasecontent) {
        diseasecontent = "症状：暂无数据";
      }
      that.setData({
        diseasecontent: diseasecontent,
      })
    }

    if (e.currentTarget.dataset.id == 'diagnosis') {
      var diseasecontent = that.data.diseasearray.diagnosis
      if (!diseasecontent) {
        diseasecontent = "诊断：暂无数据";
      }
      that.setData({
        diseasecontent: diseasecontent,
      })
    }

    if (e.currentTarget.dataset.id == 'treatment') {
      var diseasecontent = that.data.diseasearray.treatment
      if (!diseasecontent) {
        diseasecontent = "治疗：暂无数据";
      }
      that.setData({
        diseasecontent: diseasecontent,
      })
    }

    if (e.currentTarget.dataset.id == 'prevention') {
      var diseasecontent = that.data.diseasearray.prevention
      if (!diseasecontent) {
        diseasecontent = "预防：暂无数据";
      }
      that.setData({
        diseasecontent: diseasecontent,
      })
    }

    if (e.currentTarget.dataset.id == 'life') {
      var diseasecontent = that.data.diseasearray.life
      if (!diseasecontent) {
        diseasecontent = "生活：暂无数据";
      }
      that.setData({
        diseasecontent: diseasecontent,
      })
    }

    if (e.currentTarget.dataset.id == 'drug') {
      var diseasecontent = that.data.diseasearray.drug
      if (!diseasecontent) {
        diseasecontent = "药品：暂无数据";
      }
      that.setData({
        diseasecontent: diseasecontent,
      })
    }
    // console.log(that.data.diseasearray.pathogenesis);
    
  },

  pathogenesisfun: function () {
    var that = this;
    // console.log(that.data.diseasearray.pathogenesis);
    var diseasecontent = that.data.diseasearray.pathogenesis
    if (!diseasecontent){
      diseasecontent="暂无";
    }
    that.setData({
      diseasecontent: diseasecontent,
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