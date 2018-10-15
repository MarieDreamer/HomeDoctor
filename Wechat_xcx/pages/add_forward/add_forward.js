Page({
  data: {
    img_url: [],
    content:'',
    m_id:'',
    m_success:''
  },
  onLoad: function (options) {
    var a = JSON.parse(options.info)
    // var b = {
    //   "content": "dongtaitest",
    //   "id": "62",
    //   "img": ["/Uploads/Tmps/2018/08/10/5b6d28457bc28.jpg","/Uploads/Tmps/2018/08/10/5b6d28458dad6.jpg","/Uploads/Tmps/2018/08/10/5b6d284592f7a.jpg","/Uploads/Tmps/2018/08/10/5b6d2845a9614.jpg","/Uploads/Tmps/2018/08/10/5b6d2845ef20d.jpg","/Uploads/Tmps/2018/08/10/5b6d2845f2013.jpg","/Uploads/Tmps/2018/08/10/5b6d28463e69a.jpg","/Uploads/Tmps/2018/08/10/5b6d2846c4fd8.jpg","/Uploads/Tmps/2018/08/10/5b6d28472da4d.jpg"],
    //   "name": "止水"
    // };
    this.setData({
      forward: a
    })
  },
  input:function(e){
    this.data.content = e.detail.value
  },
  //发布按钮事件
  send:function(){
    var user_id = wx.getStorageSync('userid')
    wx.showLoading({
      title: '转发中',
    })    
    this.saveMoments();
  },
  saveMoments: function (){
    var that = this;
    wx.request({
      url: 'http://wechat.homedoctor.jianfengweb.com/Moments/adds',
      data: {
        user_id: wx.getStorageSync('userid'),
        images: '',
        content: that.data.content,
        m_id: '',
        fid:that.data.forward.id
      },
      success: function (res) {
        if (res.data.status == 1) {
          wx.hideLoading()
          wx.showModal({
            title: '提交成功',
            showCancel: false,
            success: function (res) {
              if (res.confirm) {
                wx.navigateTo({
                  url: '/pages/my_moments/my_moments'
                })
              }
            }
          })
        } else {
          wx.hideLoading()
          wx.showModal({
            title: '提交失败',
            showCancel: false,
            success: function (res) {
              if (res.confirm) {
                wx.navigateBack()
              }
            }
          })
        }
      }
    })
  } 
})