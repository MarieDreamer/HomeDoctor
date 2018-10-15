Page({
  data: {
    img_url: [],
    content:'',
    m_id:'',
    text:'分享动态',
    m_success:''
  },
  onLoad: function (options) {
    if(options.m_id != undefined){
      this.data.m_id = options.m_id;
      this.data.m_success = '/pages/comment_detail/comment_detail?moments_id=' + options.m_id;
      this.setData({
        text:'发表评论'
      })
    }
  },
  input:function(e){
    this.data.content = e.detail.value
  },
  chooseimage:function(){
    var that = this;
    wx.chooseImage({
      count: 9, // 默认9  
      sizeType: ['original', 'compressed'], // 可以指定是原图还是压缩图，默认二者都有  
      sourceType: ['album', 'camera'], // 可以指定来源是相册还是相机，默认二者都有  
      success: function (res) {
        
        if (res.tempFilePaths.length>0){

          //图如果满了9张，不显示加图
          if (res.tempFilePaths.length == 9){
            that.setData({
              hideAdd:1
            })
          }else{
            that.setData({
              hideAdd: 0
            })
          }

          //把每次选择的图push进数组
          let img_url = that.data.img_url;
          for (let i = 0; i < res.tempFilePaths.length; i++) {
            img_url.push(res.tempFilePaths[i])
          }
          that.setData({
            img_url: img_url
          })
          
        }
        
      }
    })  
  },
  //发布按钮事件
  send:function(){
    var that = this;
    var user_id = wx.getStorageSync('userid')
    wx.showLoading({
      title: '上传中',
    })
    if(that.data.img_url[0]){
      that.img_upload()
    }else{
      that.saveMoments('');
      console.log('2')
    }
    
  },
  //图片上传
  img_upload: function () {
    let that = this;
    let img_url = that.data.img_url;
    let img_url_ok = [];
    //由于图片只能一张一张地上传，所以用循环
    for (let i = 0; i < img_url.length; i++) {
      wx.uploadFile({
        //路径填你上传图片方法的地址
        url: 'http://wechat.homedoctor.jianfengweb.com/Moments/upload_do',
        filePath: img_url[i],
        name: 'file',
        formData: {
          'user': 'test'
        },
        success: function (res) {
          console.log('上传成功');
          //把上传成功的图片的地址放入数组中
          img_url_ok.push(res.data)
          //如果全部传完，则可以将图片路径保存到数据库
          if (img_url_ok.length == img_url.length) {
            that.saveMoments(img_url_ok);
          }
        },
        fail: function (res) {
          console.log('上传失败')
        }
      })
    }
  },
  saveMoments: function (img_url_ok){
    var that = this;
    wx.request({
      url: 'http://wechat.homedoctor.jianfengweb.com/Moments/adds',
      data: {
        user_id: wx.getStorageSync('userid'),
        images: img_url_ok,
        content: that.data.content,
        m_id: that.data.m_id
      },
      success: function (res) {
        if (res.data.status == 1) {
          wx.hideLoading()
          wx.showModal({
            title: '提交成功',
            showCancel: false,
            success: function (res) {
              var u = '';
              if (res.confirm) {
                if (that.data.m_success) {
                  u = that.data.m_success
                } else {
                  u = '/pages/my_moments/my_moments'
                }
                wx.navigateTo({
                  url: u
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