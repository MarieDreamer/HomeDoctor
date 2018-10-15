// pages/order/order.js
Page({

  /**
   * 页面的初始数据
   */
  data: {
    doctorid:"",
    addressarray:[],
    doctorarray: [],
    doctorid:'',
    inputcontent:"",
    address:"",
    patientname:"",
    patientphone:"",
    show1: false,
    show2: false,
    inputage:"",
    inputsex:"",
    disease:"输入病症",


  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    let that = this;
    var userid = wx.getStorageSync('userid');
    // console.log(options.disease);
    if (options.doctorid){
      var doctorid = options.doctorid;
    }else{
      var doctorid ="";
    }

    if (options.disease){
      var disease = options.disease
      that.setData({
        disease: disease,
        inputcontent:disease,
      })
    }
    

    if (!doctorid){
      wx.request({
        url: 'http://wechat.homedoctor.jianfengweb.com/Doctor/doctorlists',
        data: {
        },
        success: function (res) {
          // console.log(res.data.lists);
          var doctorid = res.data.lists;
          // console.log(doctorid);
          that.setData({
            doctorid: doctorid
          })

          wx.request({
            url: 'http://wechat.homedoctor.jianfengweb.com/Doctor/lists',
            data: {
              doctorid: doctorid,
            },
            success: function (res) {
              // console.log(res.data.lists[0])
              var doctorarray = res.data.lists;
              that.setData({
                doctorarray: doctorarray,
              })
            }
          })

        }
      })
    }else{
      that.setData({
        doctorid: doctorid,
      })

      wx.request({
        url: 'http://wechat.homedoctor.jianfengweb.com/Doctor/lists',
        data: {
          doctorid: doctorid,
        },
        success: function (res) {
          console.log(res.data.lists[0])
          var doctorarray = res.data.lists;
          that.setData({
            doctorarray: doctorarray,
          })
        }
      })
    }

    
    wx.request({
      url: 'http://wechat.homedoctor.jianfengweb.com/Address/lists',
      data: {
        userid: userid,
        defaults: 1,
      },
      success: function (res) {
        // console.log(res.data.lists[0])
        var addressarray = res.data.lists;
        if (addressarray){
          var address = res.data.lists[0].province + res.data.lists[0].city + res.data.lists[0].region + res.data.lists[0].address;
          var patientname = res.data.lists[0].name;
          var patientphone = res.data.lists[0].phone;
          that.setData({
            addressarray: addressarray,
            address: address,
            patientphone: patientphone,
            patientname: patientname,
            show2: false,
            show1: true,
          })
        }else{
          that.setData({
            show2: true,
            show1: false,
          })
        }
      }
    })

    
  
  },

  inputcontentfun: function (e) {
    this.setData({
      inputcontent: e.detail.value
    })
  },

  inputagefun: function (e) {
    this.setData({
      inputage: e.detail.value
    })
  },

  inputsexfun: function (e) {
    this.setData({
      inputsex: e.detail.value
    })
  },

  tijiaofun: function (e) {
    var that = this;
    var inputsex = that.data.inputsex;
    var inputage = that.data.inputage;
    var inputcontent = that.data.inputcontent;
    var address = that.data.address;
    var patientname = that.data.patientname;
    var patientphone = that.data.patientphone;
    var doctorid = that.data.doctorid;
    var userid = wx.getStorageSync('userid');
    if (address && patientname && patientphone){
      if (inputcontent) {
        wx.request({
          url: 'http://wechat.homedoctor.jianfengweb.com/Order/adds_do',
          data: {
            disease: inputcontent,
            address: address,
            patientname: patientname,
            patientphone: patientphone,
            doctorid: doctorid,
            inputage: inputage,
            inputsex: inputsex,
            userid: userid,
          },
          success: function (res) {
            console.log(res.data.message);
            if (res.data.message == "操作成功") {
              wx.showToast({
                // icon: 'none',
                title: "下单成功",
              })

              setTimeout(function () {
                wx.navigateBack({
                  url: '/pages/shangmen/shangmen',
                })
              }, 3000)
            }

          }
        })

      } else {
        wx.showToast({
          icon: 'none',
          title: "未输入病症",
        })
      }

    }else{
      wx.showToast({
        icon: 'none',
        title: "您还未填写地址",
      })
    }
    
    
    
  }, 

  //管理地址跳转
  goaddress: function (e) {
    // console.log(11454);
    wx.navigateTo({
      url: '/pages/address/address',
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
  onShow: function (e) {
    let that = this;
    var userid = wx.getStorageSync('userid');
    wx.request({
      url: 'http://wechat.homedoctor.jianfengweb.com/Address/lists',
      data: {
        userid: userid,
        defaults: 1,
      },
      success: function (res) {
        // console.log(res.data.lists[0])
        var addressarray = res.data.lists;
        if (addressarray) {
          var address = res.data.lists[0].province + res.data.lists[0].city + res.data.lists[0].region + res.data.lists[0].address;
          var patientname = res.data.lists[0].name;
          var patientphone = res.data.lists[0].phone;
          that.setData({
            addressarray: addressarray,
            address: address,
            patientphone: patientphone,
            patientname: patientname,
            show2: false,
            show1: true,
          })
        } else {
          that.setData({
            show2: true,
            show1: false,
          })
        }
      }
    })
  
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