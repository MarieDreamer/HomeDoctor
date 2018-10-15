// pages/address/address.js
// var amapFile = require('../../libs/amap-wx.js');
// var markersData = {
//   latitude: '',//纬度
//   longitude: '',//经度
//   key: "高德地图key"//申请的高德地图key
// };
// var QQMapWX = require('../../libs/qqmap-wx-jssdk.js');
// var demo = new QQMapWX({
//   key: '2FXBZ-P57W3-SCA37-YGMFC-25N3J-FUFGL' // 必填
// });

Page({

  /**
   * 页面的初始数据
   */
  data: {
    addressarray:[],
    addressdefaultsarray:[],
    region:"",
    dizhi:"",
    dizhixianshi:"选择地区",
    patientphone2:"",
    patientcard2: "",
    patientname2: "",
    tianjiaclass:"tijiaodizhinone",
    xiugaiclass:"xiugainone",
    xiugaiphone:"",
    xiugainame:"",
    xiugaidizhixianshi:"选择地区",
    xiugaidizhi:"",
    xiugaipatientcard:"",
    addressid:'',

  
  },


  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    let that = this;
    var userid = wx.getStorageSync('userid');

    // console.log(userid);

    that.displayfundefault();
    that.displayfun();
    
  },

  //添加地址
  tijiaodizhifun: function (i) {
    // 0 == e ? (n = 0, d = !1, e = 1) : (n = 200, d = !0, e = 0), t(this, n, d);
    var that = this;
    wx.chooseLocation({
      success: function (res) {
        console.log(res.address)
        var dizhi = res.address;
        var dizhixianshi = dizhi.substr(0, 12) + "...";
        that.setData({
          dizhi: dizhi,
          dizhixianshi: dizhixianshi,
        })
      },
    })
  },
  patientphonefun: function (e) {
    this.setData({
      patientphone2: e.detail.value
    })
  },
  
  patientnamefun: function (e) {
    this.setData({
      patientname2: e.detail.value
    })
  },
  patientcardfun: function (e) {
    this.setData({
      patientcard2: e.detail.value
    })
  },

  tijiaofun: function (e) {
    this.setData({
      tianjiaclass: "tijiaodizhi"
    })
  },

  tijiaochachafun: function (e) {
    this.setData({
      tianjiaclass: "tijiaodizhinone"
    })
  },

  modifyfun: function (e) {
    var that = this;
    var addressid = e.currentTarget.dataset.addressid;
    this.setData({
      xiugaiclass: "tijiaodizhi",
      addressid: addressid,

    })
  },

  xiugaichachafun: function (e) {
    var that = this;
    this.setData({
      xiugaiclass: "xiugainone"
    })
  },

  xiugaiphonefun: function (e) {
    this.setData({
      xiugaiphone: e.detail.value
    })
  },

  xiugainamefun: function (e) {
    this.setData({
      xiugainame: e.detail.value
    })
  },

  xiugaidizhi: function (i) {
    // 0 == e ? (n = 0, d = !1, e = 1) : (n = 200, d = !0, e = 0), t(this, n, d);
    var that = this;
    wx.chooseLocation({
      success: function (res) {
        console.log(res.address)
        var dizhi = res.address;
        var dizhixianshi = dizhi.substr(0, 12) + "...";
        that.setData({
          xiugaidizhi: dizhi,
          xiugaidizhixianshi: dizhixianshi,
        })
      },
    })
  },

  xiugaipatientcardfun: function (e) {
    this.setData({
      xiugaipatientcard: e.detail.value
    })
  },

  



  defaultchangefun: function (e) {
    var that = this;
    var userid = wx.getStorageSync('userid');
    // console.log(e.currentTarget.dataset.defaultid);
    var id = e.currentTarget.dataset.defaultid;
    wx.request({
      url: 'http://wechat.homedoctor.jianfengweb.com/Address/defaultchange',
      data: {
        id: id,
        userid: userid,
      },
      success: function (res) {
        console.log(res.data.message)
        if (res.data.message == "操作成功") {
          wx.showToast({
            icon: 'none',
            title: "设置成功",
          })

          that.displayfun();
          that.displayfundefault();

        }else{
          wx.showToast({
            icon: 'none',
            title: res.data.message,
          })
        }
      }
    })
    
  },

  //删除地址
  deletefun: function (e) {
    var that = this;
    var userid = wx.getStorageSync('userid');
    // console.log(e.currentTarget.dataset.deleteid);
    var id = e.currentTarget.dataset.deleteid;
    wx.request({
      url: 'http://wechat.homedoctor.jianfengweb.com/Address/deletefun',
      data: {
        id: id,
        userid: userid,
      },
      success: function (res) {
        console.log(res.data.message)
        if (res.data.message =="操作成功"){
          wx.showToast({
            icon: 'none',
            title: "删除成功",
          })

          setTimeout(function () {
            that.displayfun();

          }, 1000)

        }
      }
    })
  },

  //修改地址
  xiugaifungo: function (e) {
    var that = this;
    var userid = wx.getStorageSync('userid'); addressid
    var addressid = that.data.addressid;
    var name = that.data.xiugainame;
    var phone = that.data.xiugaiphone;
    var province = that.data.xiugaidizhi;
    var address = that.data.xiugaipatientcard;

    var myreg = /^[1][1,2,3,4,5,7,8,9,0][0-9]{9}$/;

    if (!myreg.test(phone)){
      wx.showToast({
        icon: 'none',
        title: "手机号格式不正确",
      })
    } else{
      if (name && phone && province && address){
        wx.request({
          url: 'http://wechat.homedoctor.jianfengweb.com/Address/modify_do',
          data: {
            userid: userid,
            id: addressid,
            name: name,
            phone: phone,
            province: province,
            address: address,
          },
          success: function (res) {
            console.log(res.data.message);
            if (res.data.message == "操作成功") {
              wx.showToast({
                icon: 'none',
                title: "修改成功",
              })
              setTimeout(function () {
                that.setData({
                  xiugaiclass: "xiugainone",
                })
              }, 1)
              that.displayfun();
              that.displayfundefault();
            }
          }
        })
      }else{
        wx.showToast({
          icon: 'none',
          title: "缺少必要数据",
        })
      }
    }

  },

  //添加地址
  tijiaofungo: function (i) {
    var that = this;
    var userid = wx.getStorageSync('userid');
    var dizhi = that.data.dizhi;
    var patientphone2 = that.data.patientphone2;
    var patientname2 = that.data.patientname2;
    var patientcard2 = that.data.patientcard2;
    console.log(userid);
    var myreg = /^[1][1,2,3,4,5,7,8,9,0][0-9]{9}$/;
    if (!myreg.test(patientphone2)) {
      wx.showToast({
        icon: 'none',
        title: "手机号格式不正确",
      })
    } else {
      if (patientphone2 && patientname2 && patientcard2 && dizhi){

        wx.request({
          url: 'http://wechat.homedoctor.jianfengweb.com/Address/adds_do',
          data: {
            userid: userid,
            dizhi: dizhi,
            patientphone2: patientphone2,
            patientname2: patientname2,
            patientcard2: patientcard2,
          },
          success: function (res) {
            console.log(res.data.message);
            if (res.data.message == "操作成功"){
              wx.showToast({
              icon: 'none',
              title: "提交成功",
            })
            setTimeout(function () {
              that.setData({
                tianjiaclass: "tijiaodizhinone",
              })
            }, 1)
            that.displayfun();
            that.displayfundefault();
            }
          }
        })
      }else{
        wx.showToast({
          icon: 'none',
          title: "缺少必要数据",
        })
      }
    }



  },

  //加载非默认地址
  displayfun: function (e) {
    console.log("加载非默认地址")
    var that = this;
    var userid = wx.getStorageSync('userid');
    wx.request({
      url: 'http://wechat.homedoctor.jianfengweb.com/Address/lists',
      data: {
        userid: userid,
        defaults: 0,
      },
      success: function (res) {
        // console.log(res.data.lists[0])
        var addressdefaultsarray = res.data.lists;
        // console.log(patientname);
        that.setData({
          addressdefaultsarray: addressdefaultsarray,
        })
      }
    })
  },

  //加载默认地址
  displayfundefault: function (e) {
    console.log("加载默认地址")
    var that = this;
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
        // console.log(patientname);
        that.setData({
          addressarray: addressarray,
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
  onShow: function (e) {
    
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