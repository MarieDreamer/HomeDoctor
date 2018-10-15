//index.js
//获取应用实例
const app = getApp()

Page({
  data: {
    motto: 'Hello World',
    userInfo: {},
    hasUserInfo: false,
    canIUse: wx.canIUse('button.open-type.getUserInfo'),
    tabs:[
      { 'value': '关注', 'selected': false },
      { 'value': '推荐', 'selected': true },
      { 'value': '热榜', 'selected': false }
    ]
  },
  onLoad: function () {
    this.data.page = 1;
    if (app.globalData.userInfo) {
      this.setData({
        userInfo: app.globalData.userInfo,
        hasUserInfo: true
      })
    } else if (this.data.canIUse) {
      // 由于 getUserInfo 是网络请求，可能会在 Page.onLoad 之后才返回
      // 所以此处加入 callback 以防止这种情况
      app.userInfoReadyCallback = res => {
        this.setData({
          userInfo: res.userInfo,
          hasUserInfo: true
        })
      }
    } else {
      // 在没有 open-type=getUserInfo 版本的兼容处理
      wx.getUserInfo({
        success: res => {
          app.globalData.userInfo = res.userInfo
          this.setData({
            userInfo: res.userInfo,
            hasUserInfo: true
          })
        }
      })
    }
    this.requestMoments(1,0);
  },
  requestMoments: function(e,pull){
    wx.request({
      url: 'http://wechat.homedoctor.jianfengweb.com/Moments/community',
      data:{
        user_id: wx.getStorageSync('userid'),
        page: this.data.page,
        tag: e
      },
      success: res => {
        if(pull){
          wx.hideNavigationBarLoading();
          wx.stopPullDownRefresh();
        }
        if(this.data.page == 1){
          var moments = res.data.data;
          var moments_like = [];
          for (var v in moments) {
            moments_like.push([moments[v].isLike, moments[v].like_num]);
          }
          this.setData({
            moments: res.data.data,
            moments_like: moments_like
          })
        }
        else{
          wx.hideLoading();
          if (res.data.data != null) {
            var moments = this.data.moments;
            for (var v in res.data.data)
              moments.push(res.data.data[v]);
            var moments_like = []
            for (var v in moments) {
              moments_like.push([moments[v].isLike, moments[v].like_num]);
            }
            this.setData({
              moments: moments,
              moments_like: moments_like
            })
          } else {
            this.setData({
              show_bottom: 1
            })
          }    
        }
      }
    })
  },
  getUserInfo: function (e) {
    app.globalData.userInfo = e.detail.userInfo
    this.setData({
      userInfo: e.detail.userInfo,
      hasUserInfo: true
    })
  },
  tabChange: function (e) {
    var index = e.currentTarget.dataset.index;
    var arr = this.data.tabs;
    this.data.page = 1;
    this.setData({
      show_bottom: 0
    })
    for (var v in arr) {
      v == index ? (arr[v].selected = true, this.requestMoments(index, 0)) : arr[v].selected = false;
    }
    this.setData({
      tabs: arr
    })
  },
  preview: function (e) {
    var src = e.currentTarget.dataset.src;//获取data-src
    var imgList = e.currentTarget.dataset.list;//获取data-list
    var index = e.currentTarget.dataset.index;//获取data-list

    for (var v in imgList)
      imgList[v] = "http://wechat.homedoctor.jianfengweb.com/" + imgList[v];
    for (var i = 0; i < index; i++) {
      imgList.push(imgList[i])
    }
    imgList.splice(0, index);
    //图片预览
    wx.previewImage({
      current: src, // 当前显示图片的http链接
      urls: imgList // 需要预览的图片http链接列表
    })
  },
  like: function (e) {
    wx.request({
      url: 'http://wechat.homedoctor.jianfengweb.com/Moments/like',
      data: {
        user_id: wx.getStorageSync('userid'),
        moments_id: e.currentTarget.dataset.id
      },
      success: res => {
        var index = e.currentTarget.dataset.index;
        var moments_like = this.data.moments_like;
        if (moments_like[index][0] == 2) {
          moments_like[index][0] = 1;
          moments_like[index][1]++;
        } else if (moments_like[index][0] == 1) {
          moments_like[index][0] = 2;
          moments_like[index][1]--;
        }
        this.setData({
          moments_like: moments_like
        })
      }
    })
  },
  comment_detail: function (e) {
    wx.navigateTo({
      url: '../comment_detail/comment_detail?moments_id=' + e.currentTarget.dataset.id
    })
  },
  forward: function (e) {
    var a = JSON.stringify(e.currentTarget.dataset);
    wx.navigateTo({
      url: '../add_forward/add_forward?info=' + a
    })
  },
  person: function (e) {
    wx.navigateTo({
      url: '../person_detail/person_detail?user_id=' + e.currentTarget.dataset.user_id
    })
  },
  addMoments:function(){
    wx.navigateTo({
      url: '/pages/add_moments/add_moments',
    })
  },
  onPullDownRefresh:function(){
    wx.showNavigationBarLoading();
    var tabs = this.data.tabs;
    var e = '';
    for (var v in tabs)
      tabs[v]['selected'] == true ? e = v : '';
    this.data.page = 1;
    this.setData({
      show_bottom: 0
    })
    this.requestMoments(e,1);
  },
  onReachBottom:function(){
    wx.showLoading({
      title: '玩命加载中',
    })
    var e = '';
    var tabs = this.data.tabs;
    for (var v in tabs)
      tabs[v]['selected'] == true ? e = v : '';
    this.data.page ++;
    this.requestMoments(e, 0);
  },
  onShareAppMessage: function (res) {
    if (res.from === 'menu') {
      // 来自页面内转发按钮
      console.log(res.target)
    }
    return {
      title: '家庭医生',
      path: '/page/index?id=123',
      success: function (res) {
        // 转发成功
      },
      fail: function (res) {
        // 转发失败
      }
    }
  }
  
})
