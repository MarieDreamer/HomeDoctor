// pages/diseaselookup/diseaselookup.js
Page({

  /**
   * 页面的初始数据
   */
  data: {
    windowHeight:'',
    keyquery:[],
    sonquery: [],
    ABC: ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'],
    queryfun:"",
    sonarray:[],
    promptarray:"暂无数据",
    show1:false,
    show2:false,
    show3: false,
    show4: false,
    show5: false,
    diseaseid:'',
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    var that = this;
    // console.log(options.bodydisease)
    //判断是否是首字母查询进入
    if (options.letter == 'letterchoice'){
      var keyquery = that.data.ABC;
      that.setData({
        keyquery: keyquery,
        queryfun: 'choicezm',
        show3: true,
        show4: false,
      })
    }

    //部位查询进入
    if (options.bodydisease == 'position') {
      wx.request({
        url: 'http://wechat.homedoctor.jianfengweb.com/BodyDisease/bodydiseaselists',
        data: {
        },
        success: function (res) {
          console.log(res.data.bodydiseaselists)
          var keyquery = res.data.bodydiseaselists;
            that.setData({
              keyquery: keyquery,
              queryfun: 'querysonfun',
              show3: false,
              show4: true,
            })
        }
      })
    }
    // console.log(options.department)
    if (options.department == 'department') {
      wx.request({
        url: 'http://wechat.homedoctor.jianfengweb.com/Department/departmentlists',
        data: {
        },
        success: function (res) {
          console.log(res.data.departmentlists)
          var keyquery = res.data.departmentlists;
          that.setData({
            keyquery: keyquery,
            queryfun: 'departmentpidfun',
            show3: false,
            show4: true,
            
          })
        }
      })
    }

    //检测屏幕高度函数
    wx.getSystemInfo({
      success: function (res) {
        // console.log(res.windowHeight)
        var windowHeight = res.windowHeight;
        // console.log(windowHeight)
        that.setData({
          windowHeight: windowHeight,
        })
      }
    })

  
  },

  choicezm: function (e) {
    var that = this;
    // console.log(e.currentTarget.dataset.zm);
    wx.request({
      url: 'http://wechat.homedoctor.jianfengweb.com/Disease/lists',
      data: {
        firstletter: e.currentTarget.dataset.zm,
      },
      success: function (res) {
        // console.log(res.data.lists);
        var sonarray = res.data.lists;
        console.log(sonarray);
        if (!sonarray){
          that.setData({
            show2: true,
            show1: false,
            show5: false,
            
          })
        }else{
          that.setData({
            sonarray: sonarray,
            show1: true,
            show2: false,
            show5: false,

          })
        }
        
      }
    })
  },

  querysonfun: function (e) {
    var that = this;
    console.log(e.currentTarget.dataset.st);
    wx.request({
      url: 'http://wechat.homedoctor.jianfengweb.com/Disease/lists',
      data: {
        bodyposition: e.currentTarget.dataset.st,
      },
      success: function (res) {
        console.log(res.data);
        var sonarray = res.data.lists;
        // console.log(sonarray);
        if (!sonarray) {
          that.setData({
            show2: true,
            show1: false,
            show5: false,
          })
        } else {
          that.setData({
            sonarray: sonarray,
            show1: true,
            show2: false,
            show5: false,

          })
        }

      }
    })
  },

  departmentpidfun: function (e) {
    var that = this;
    console.log(e.currentTarget.dataset.st);
    wx.request({
      url: 'http://wechat.homedoctor.jianfengweb.com/Department/departmentlists',
      data: {
        pid: e.currentTarget.dataset.st,
      },
      success: function (res) {
        // console.log(res.data.departmentlists);
        var sonarray = res.data.departmentlists;
        // console.log(sonarray);
        if (!sonarray) {
          wx.request({
            url: 'http://wechat.homedoctor.jianfengweb.com/Department/departmentlists',
            data: {
              id: e.currentTarget.dataset.st,
            },
            success: function (res) {
              console.log(res.data.departmentlists);
              var sonarray = res.data.departmentlists;
              // console.log(sonarray);
              that.setData({
                sonarray: sonarray,
                show1: false,
                show2: false,
                show5: true,
              })

            }
          })
        } else {
          that.setData({
            sonarray: sonarray,
            show1: false,
            show2: false,
            show5: true,

          })
        }

      }
    })
  },

  sonfun: function (e) {
    var that = this;
    // console.log(e.currentTarget.dataset.diseaseid);
    var diseaseid = e.currentTarget.dataset.diseaseid;
    wx.navigateTo({
      url: "/pages/diseasedetailed/diseasedetailed?diseaseid=" + diseaseid,
    });
  },

  departmentsun: function (e) {
    var that = this;
    console.log(e.currentTarget.dataset.diseaseid);
    var diseaseid = e.currentTarget.dataset.diseaseid;

    wx.request({
      url: 'http://wechat.homedoctor.jianfengweb.com/Disease/lists',
            data: {
              id: diseaseid,
            },
            success: function (res) {
              console.log(res.data.lists);
              var sonarray = res.data.lists;
              // console.log(sonarray);
              that.setData({
                sonarray: sonarray,
                show1: true,
                show2: false,
                show5: false,
              })

            }
          })
    // wx.navigateTo({
    //   url: "/pages/diseasedetailed/diseasedetailed?diseaseid=" + diseaseid,
    // });
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