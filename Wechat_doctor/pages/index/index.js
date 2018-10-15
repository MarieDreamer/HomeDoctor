//index.js
//获取应用实例
const app = getApp()

Page({
  data: {
    choicedivclass:'orderchoiceblue',
    choicedivrightclass: 'orderchoice',
    conductclass: 'orderchoice',
    neworder:'',
    record:'display',
    // 前台显示数组
    orderinfo:[],
    // 页数
    page:1
  },

  goneworder: function () {
    let that = this;
    that.setData({
      choicedivclass: 'orderchoiceblue',
      choicedivrightclass: 'orderchoice',
      neworder: '',
      record: 'display',
      conductclass: 'orderchoice',
    })
    wx.request({
      url: 'http://wechat.homedoctor.jianfengweb.com/Order/lists',
      data: {
        receipt: 1,
        page: 1,
      },
      success: function (res) {
        // console.log(res.data.lists)
        var orderinfo = res.data.lists;
        that.setData({
          orderinfo: orderinfo,
        })
      }
    }) 
  },

  gorecord: function () {
    let that = this;
    var userid = wx.getStorageSync('userid');
    that.setData({
      choicedivclass: 'orderchoice',
      choicedivrightclass: 'orderchoiceblue',
      neworder: 'display',
      record: '',
      conductclass: 'orderchoice',
    })
    wx.request({
      url: 'http://wechat.homedoctor.jianfengweb.com/Order/lists',
      data: {
        receipt:3,
        page: 1,
        doctorid: userid,
      },
      success: function (res) {
        // console.log(res.data.lists)
        var orderinfo = res.data.lists;
        that.setData({
          orderinfo: orderinfo,
        })
      }
    })
  },

  conductfun: function () {
    let that = this;
    var userid = wx.getStorageSync('userid');
    that.setData({
      choicedivclass: 'orderchoice',
      choicedivrightclass: 'orderchoice',
      neworder: '',
      record: 'display',
      conductclass: 'orderchoiceblue',
    })
    wx.request({
      url: 'http://wechat.homedoctor.jianfengweb.com/Order/lists',
      data: {
        receipt: 0,
        page: 1,
        doctorid: userid,
      },
      success: function (res) {
        // console.log(res.data.lists)
        var orderinfo = res.data.lists;
        that.setData({
          orderinfo: orderinfo,
        })
      }
    })
  },

  //病人病例页面
  gopatient_case: function (e) {
    console.log(e.currentTarget.dataset.st);
    wx.navigateTo({
      url: '/pages/patient_case/patient_case?id=' + e.currentTarget.dataset.st,
    })
  },
  //事件处理函数
  bindViewTap: function() {
  },
  onLoad: function () {
    var that = this;
    wx.request({
      url: 'http://wechat.homedoctor.jianfengweb.com/Order/lists',
      data: {
        receipt:1,
        page:1,
      },
      success: function (res) {
        // console.log(res.data.lists)
        var orderinfo=res.data.lists;
        that.setData({
          orderinfo: orderinfo,
        })
      }
    })
  },
  // 上拉分页加载
  onReachBottom: function () {
    var self=this;
    // 当前页+1
    var page=self.data.page+1;
    self.setData({
      page: page,
    })

    wx.showLoading({
      title: '加载中',
    })
    wx.request({
      url: 'http://wechat.homedoctor.jianfengweb.com/Order/lists',
      data: {
        page: page,
        receipt: 1,
      },
      success: function (res) {
        wx.hideLoading();
        // console.log(self.data.orderinfo)
        // console.log(res.data.lists)
        // 第一个数组
        var orderinfo=self.data.orderinfo;
        // 第二个数组
        var orderinfo1 = res.data.lists;
        // 第二个数组不为空时，数组合并
        if(orderinfo1!=null){
          var orderinfo2 = orderinfo.concat(orderinfo1);
        }
        else{
          var orderinfo2=orderinfo;
          wx.showToast({
            title: '没有更多订单',
            duration:2000
          })
        }
        // 将新获取的数据 res.data.list，concat到前台显示的orderinfo中即可。
        self.setData({
          orderinfo: orderinfo2,
        })
      }
   })
  },
  // 下拉刷新
  onPullDownRefresh: function(){
    var that = this;
    wx.showLoading({
      title: '刷新中',
    })
    that.setData({
      choicedivclass: 'orderchoiceblue',
      choicedivrightclass: 'orderchoice',
      neworder: '',
      record: 'display',
      conductclass: 'orderchoice',
    })
    wx.request({
      url: 'http://wechat.homedoctor.jianfengweb.com/Order/lists',
      data: {
        receipt: 1,
        page: 1,
      },
      success: function (res) {
        // console.log(res.data.lists)
        wx.hideLoading();
        var orderinfo = res.data.lists;
        that.setData({
          orderinfo: orderinfo,
        })
        
      }
    })
  },
  
  // 获取id，发送后台
  submitInfo: function (e) {
  console.log(e.detail.formId);
  }
})
