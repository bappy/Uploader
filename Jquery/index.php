<?php include("config.php"); ?>
<script type="text/javascript" src="jquery.js"></script>
<script type="text/javascript" src="scripts/jquery.imgareaselect.pack.js"></script>
<link rel="stylesheet" type="text/css" href="css/imgareaselect-default.css" />

    <script type="text/javascript">

        (function($){

        jQuery.fn.ImUploader=function(options){
        var defaults={
            thischooseFile:"cfile",
            Uploadbtn:"ubtn",
            ProgressBar:"Pbar",
            DivOrignail:"Output",
            Croppedbtn:"mybutton",
            DivMsg:"msg",
            DivProgressNum:"prgogressNumber",
            formId:"fom1",
            photo:"photo",
            meter:"meter",
            chooseFile:"fileToUpload",
            cropped:"output",
            working:"busy"


        };

        var o=jQuery.extend(defaults,options);


            return this.each(function(){

             var imageUpload=false;
             var dfd = new jQuery.Deferred();
             $(this).prepend('<input type="file" name="fileToUpload" id="'+ o.chooseFile+'" accept="image/*"/>');
             $(this).append('<input type="button" id="'+ o.Uploadbtn+'" value="Upload" />');
             $(this).prepend('<div id="'+ o.ProgressBar+'"></div>');
             $(this).append('<p><progress  id="'+ o.meter+'" value="0" min="0" max="100">2 out of 10</progress></p>');
             $(this).append('<p><div id="'+ o.DivProgressNum+'"></p>');
             $(this).append('<p><button disabled  id="'+ o.Croppedbtn+'">Crop</button></p>');
             $(this).append('<p><img id="'+ o.photo+'" src=""></p>');
             $(this).append('<p><img id="'+ o.cropped+'" src=""></p>');
             $(this).append('<p><div style="display:none;" id="'+ o.working+'">working.....</div></p>');
             $(this).append('<p><div id="'+ o.DivMsg+'"></div></p>');





                $("#"+o.Uploadbtn).click(function() {
                    var fd = new FormData();
                    fd.append("fileToUpload",document.getElementById(o.chooseFile).files[0])
                    var xhr = new XMLHttpRequest();
                    xhr.upload.addEventListener("progress", uploadProgress, false);
                    xhr.addEventListener("load", uploadComplete, false);
                    xhr.addEventListener("error", uploadFailed, false);
                    xhr.addEventListener("abort", uploadCanceled, false);

                    xhr.open("POST", "UploadMinimal.php");
                    xhr.send(fd);

                });


                function selection_process(selection)
                {

                    var array=new Array();
                    array[0]=  selection.x1;
                    array[1]=  selection.x2;
                    array[2]=selection.y1;
                    array[3]=selection.y2;
                    array[4]=  selection.width;
                    array[5]=  selection.height;
                    var string= JSON.stringify(array);

                    return string;

                }


                function CropComplete(evt) {




                    var extension = evt.target.responseText;



                    if(extension==2)
                    {
                        $("div#"+ o.DivMsg).text('Load Not Successful From S3.AMAZON,PLEASE PRESS THE "Crop" Button AGAIN ')
                    }
                    else
                    {

                        $("#"+ o.cropped).attr("src",'');
                        $("#"+ o.cropped).attr("src",evt.target.responseText);
                        $("div#"+ o.working).hide();
                        $("div#"+ o.DivMsg).text('Load Successful')
                    }

                }

                function uploadProgress(evt) {
                    if (evt.lengthComputable) {
                        var percentComplete = Math.round(evt.loaded * 100 / evt.total);
                        $("#"+ o.meter).val(percentComplete);
                        $("#"+ o.DivProgressNum).text(percentComplete.toString() + '%');
                    }
                    else {
                        document.getElementById('progressNumber').innerHTML = 'unable to compute';
                    }
                }



                function uploadComplete(evt) {


                    var img=evt.target.responseText;


                    if(evt.target.responseText==2)
                    {
                        $("div#"+ o.DivMsg).text('Image size is grater than than'+<?php echo size_limit ?>)
                        return;
                    }

                    $("img#"+ o.photo).attr("src",img);
                    $("#"+ o.Croppedbtn).removeAttr("disabled");


                }

                function uploadFailed(evt) {
                    alert("There was an error attempting to upload the file.");
                }

                function uploadCanceled(evt) {
                    alert("The upload has been canceled by the user or the browser dropped the connection.");
                }

                var clck= $("#"+ o.Croppedbtn)
                $("img#"+ o.photo).imgAreaSelect({
                    onSelectEnd: function (img, selection) {
                        clck.click(function() {

                            $("div#"+ o.DivMsg).text('')
                            var string= selection_process(selection);

                            var fd = new FormData();
                            fd.append("cropImage",$(img).attr("src"));
                            fd.append("cropSection",string);
                            var xhr = new XMLHttpRequest();
                            xhr.addEventListener("load", CropComplete, false);
                            xhr.open("POST", "UploadCrop.php");
                            xhr.send(fd);


                            if ( dfd.state() === "pending" ) {
                                $("div#"+ o.working).show();


                            }


                        })

                    }

                })

            });


        };





        })(jQuery)
        $(document).ready(function(){
            $("p#uploader").ImUploader();
            var options={
                thischooseFile:"cfile1",
                Uploadbtn:"ubtn1",
                ProgressBar:"Pbar1",
                DivOrignail:"Output1",
                Croppedbtn:"mybutton1",
                DivMsg:"msg1",
                DivProgressNum:"prgogressNumber1",
                formId:"fom11",
                photo:"photo1",
                meter:"meter1",
                chooseFile:"fileToUpload1",
                cropped:"output1",
                working:"busy1"


            };
            $("p#uploader1").ImUploader(options);
        });
    </script>
<p id="Uploader"></p>



 <p>
     <p>
<p id="Uploader1"></p>
    </p>

 </p>