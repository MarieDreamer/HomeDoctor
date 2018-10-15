Page({
  data: {
    show_bottom:''
  },
  onLoad: function (options) {
    this.data.page = 1;
    this.data.m_id = options.moments_id;
    this.getMoments();
  },
  getMoments: function (){
    var that = this;
    var user_id = wx.getStorageSync('userid')
    wx.request({
      url: 'http://wechat.homedoctor.jianfengweb.com/Moments/community',
      data: {
        moments_id: this.data.m_id,
        user_id:user_id,
        page: this.data.page,
        tag:4
      },
      success: function (res) {
        var moments = res.data.data;
        var moments_like = []
        for (var v in moments){
          moments_like.push([moments[v].isLike, moments[v].like_num]);
        }
        that.setData({
          moments:res.data.data,
          moments_like: moments_like
        })
      }
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
        console.log(moments_like[index]);
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
      url: '../comment_detail/comment_detail?moments_id=' + e.currentTarget.dataset.id,
    })
  },
  addMoments: function (e) {
    wx.navigateTo({
      url: '/pages/add_moments/add_moments?m_id=' + this.data.m_id,
    })
  },
  forward: function (e) {
    var a = JSON.stringify(e.currentTarget.dataset);
    wx.navigateTo({
      url: '../add_forward/add_forward?info=' + a
    })
  },
  onReachBottom: function () {
    var that = this;
    wx.showLoading({
      title: '玩命加载中',
    })
    wx.request({
      url: 'http://wechat.homedoctor.jianfengweb.com/Moments/community',
      data: {
        moments_id: this.data.m_id,
        user_id: wx.getStorageSync('userid'),
        page: ++this.data.page,
        tag:4
      },
      success: function (res) {
        wx.hideLoading();
        if (res.data.data != null) {
          var moments = that.data.moments;
          for (var v in res.data.data)
            moments.push(res.data.data[v]);
          var moments_like = []
          for (var v in moments) {
            moments_like.push([moments[v].isLike, moments[v].like_num]);
          }
          that.setData({
            moments: moments,
            moments_like: moments_like
          })
        } else {
          that.setData({
            show_bottom: 1
          })
        }
      }
    })
  },
  
})