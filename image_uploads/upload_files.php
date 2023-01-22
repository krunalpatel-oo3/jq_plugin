<!DOCTYPE html>
<head>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.1/dist/jquery.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
  <style type="text/css">
  	html * {box-sizing: border-box;}
    p {margin: 0;}
    .upload {padding: 40px;}
    .upload__inputfile {
      width: .1px;
      height: .1px;
      opacity: 0;
      overflow: hidden;
      position: absolute;
      z-index: -1;
    }
    .upload__btn {
      display: inline-block;
      font-weight: 600;
      color: #fff;
      text-align: center;
      min-width: 116px;
      padding: 5px;
      transition: all .3s ease;
      cursor: pointer;
      border: 2px solid;
      background-color: #4045ba;
      border-color: #4045ba;
      border-radius: 10px;
      line-height: 26px;
      font-size: 14px;
    }
    .upload__btn:hover {
      background-color: unset;
      color: #4045ba;
      transition: all .3s ease;
    }
    .upload__btn-box {margin-bottom: 10px;}
    .upload__img-wrap{display: flex;flex-wrap: wrap;margin: 0 -10px;}
    
    .upload__btn-box {margin-bottom: 12px;}
    
    .upload__img-close {
      width: 24px;
      height: 24px;
      border-radius: 50%;
      background-color: rgba(0, 0, 0, 0.5);
      position: absolute;
      top: 10px;
      right: 10px;
      text-align: center;
      line-height: 24px;
      z-index: 1;
      cursor: pointer;
    }
   .upload__img-close:after {
      content: '\2716';
      font-size: 14px;
      color: white;
    }
    .img-bg {
      background-repeat: no-repeat;
      background-position: center;
      background-size: cover;
      position: relative;
      padding-bottom: 100%;
    }
    .card-img-top{width: 245px;}
  </style>
</head>
<body>
  
  <div class="container">
    <h1>Upload Images (Base64 Image)</h1>

    <form method="post" enctype="multipart/form-data">
    	<div class="upload__box">
    	  <div class="upload__btn-box">
    	    <label class="upload__btn">
    	      <p><i class="fa fa-upload"> </i>Upload images</p>
    	      <input type="file" multiple="" data-max_length="2" class="upload__inputfile">
    	    </label>
    	  </div>
    	  <div class="upload__img-wrap"></div>
    	</div>

    	<input type="submit" name="save" class="btn btn-success">
    </form>
  </div>
</body>
</html>

<script type="text/javascript">
jQuery(document).ready(function () {
  ImgUpload();
});

function ImgUpload() {
  var imgWrap = "";
  var imgArray = [];

  $('.upload__inputfile').each(function () {
    $(this).on('change', function (e) {
      console.log(imgArray);
      imgWrap = $(this).closest('.upload__box').find('.upload__img-wrap');
      console.log(imgWrap);
      var maxLength = $(this).attr('data-max_length');
      var files = e.target.files;
      var filesArr = Array.prototype.slice.call(files);
      var iterator = 0;
      filesArr.forEach(function (f, index) {
        // if (!f.type.match('image.*')) {
        if (!f.type.match('image/jpeg|image/png|image/jpg|image/gif')) {
          alert('The following formats must be used: jpg, jpeg, png, and gif.');
          return;
        }

        if (imgArray.length > maxLength) {
          alert('Exceed limit of the file upload.');
          return false;
        } else {
          var len = 0;
          for (var i = 0; i < imgArray.length; i++) {
            if (imgArray[i] !== undefined) {
              len++;
            }
          }
          if (len > maxLength) {
            alert('Exceed limit of the file upload.');
            return false;
          } else {
            imgArray.push(f);

            var reader = new FileReader();
            reader.onload = function (e) {
              var html = '<div class="upload__img-box"><div class="card">'
                          +'<img class="card-img-top" src="'+e.target.result+'" alt="Card image cap">'
                          +'<div class="card-body">'
                            +'<p class="card-text"><input type="text" name="caption[]" class="form-control"></div> <input type="hidden" name="product_image[]" value="'+ e.target.result+'"> </p>'
                          +'<div class="upload__img-close" data-file="'+f.name+'"></div>'
                          +'</div>'
                        +'</div></div>';
              imgWrap.append(html);
              iterator++;
            }
            reader.readAsDataURL(f);
          }
        }
      });
    });
  });

  $('body').on('click', ".upload__img-close", function (e) {
    var file = $(this).attr("data-file");
    var element = $(this);
    // var id = $(this).attr('data-att-id');
    var image_wrap = $(element).closest('.upload__img-box');

    for (var i = 0; i < imgArray.length; i++) {
      if (imgArray[i].name === file) {
        console.log('same....');
        imgArray.splice(i, 1);
        break;
      }
    }
    console.log(imgArray);
     /* !! Remove the image wrap div !! */
    // jQuery(image_wrap).fadeOut(400, function(){
      $(image_wrap).remove();
      /* !! Reindex value. !! */
      jQuery("input[name='cover[]']").each(function(key, val) {
        if(isNaN(this.value)){
          $(this).val('new_'+key);
        }
      });
    // });
  });
}
</script>

<?php 
if(!empty($_POST['save'])){
  //Upload multiple files.
	foreach($_POST['product_image'] as $value){

		$data = $value;

		list($type, $data) = explode(';', $data);
		list(, $data)      = explode(',', $data);
		$data = base64_decode($data);

		file_put_contents('uploads/image_'.rand().'.png', $data);
	}
	echo '<pre>';
	print_r($_POST);
}
?>