<div class="content-left">
    <div class="content-title">
        <h1><?php echo $course->name ?></h1>
    </div>
    <div class="content-lesson">
        <div class="content-lesson-title">
            <h3>CHAPTERS</h3>
        </div>
        <?php foreach ($course->chapters as $chapter): ?>
            <?php if ($repository->memberAllowChapter($chapter)): ?>
                <a href="<?php echo site_url('course/showchapter/'.$course->slug.'/chapter-'.$chapter->order) ?>" class="btn btn-block text-xs-left">
                    <div class="card card-success">
                        <div class="card-block">
                            <span>CHAPTER <?php echo $chapter->order ?></span>
                            <h4><?php echo $chapter->name ?></h4>
                        </div>
                    </div>
                </a>
            <?php else: ?>
                <div class="has-attention">
                    <div class="attention pull-right">
                        <button class="btn btn-danger btn-konsul" data-toggle="tooltip" data-placement="top" title="Anda harus menyelesaikan Chapter <?php echo $chapter->order-1 ?> terlebih dahulu"><i class="fa fa-warning"></i></button>
                    </div>
                    <a href="#" class="btn btn-block disabled text-xs-left">
                        <div class="card card-grey">
                            <div class="card-block">
                                <span>CHAPTER <?php echo $chapter->order ?></span>
                                <h4><?php echo $chapter->name ?></h4>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endif ?>
        <?php endforeach ?>
    </div>
</div>

<!-- START EXAM -->
<div class="content-exam">
    <h4>EXAM</h4>
    <hr>
    <?php if ($repository->memberAllowExam($course)): ?>
        <?php if ($course->hasExam()): ?>
            <?php if ($course_member_status->first()->status !== 'finished'): ?>
                <a href="<?php echo site_url('course/showexam/'.$course->slug) ?>" class="btn btn-block btn-exam btn-primary">START EXAM</a>
            <?php else: ?>
                <p class="alert alert-success">Anda sudah menyelesaikan ujian</p>
                
                <!-- Start Button Trigger Modal-->
                <button type="button" course-id="<?php echo $course->id ?>" class="btn btn-primary btn-exam-score-learner" data-toggle="modal" data-target="#exam-scores-learner"><i class="fa fa-list"></i> Lihat Skor</button>
                <!-- End Button Trigger Modal -->
            
            <?php endif ?>
        <?php else: ?>
            
            <?php if ($course_member_status->first()->status == 'finished'): ?>
                <p class="alert alert-success">Anda sudah menyelesaikan ujian</p>
                
                <!-- Start Button Trigger Modal-->
                <button type="button" course-id="<?php echo $course->id ?>" class="btn btn-primary btn-exam-score-learner" data-toggle="modal" data-target="#exam-scores-learner"><i class="fa fa-list"></i> Lihat Skor</button>
                <!-- End Button Trigger Modal -->
            <?php else: ?>
               <p>Tidak ada ujian</p>
            <?php endif ?>
        <?php endif ?>
    <?php else: ?>
        <p class="alert alert-warning">Anda harus menyelesaikan semua chapter terlebih dahulu untuk memulai ujian.</p>
    <?php endif ?>
</div>
<!-- END EXAM -->

<br><br>
<!-- SERTIFIKAT -->
<div class="content-setifikat">
    <h4>SERTIFIKAT</h4>
    <hr>
    
    <?php foreach ($course_member_status as $key => $value): ?>

        <?php if ($value->status == 'finished'): ?>
            <?php if ($examscore->getScore() >= $course->passing_standards ): ?>
                <a href="<?php echo site_url('course/printedcertificate/'.$course->slug) ?>" class="btn btn-block btn-sertifikat btn-info">CETAK SERTIFIKAT</a>
            <?php else: ?>
                <div class="alert alert-warning">
                    Maaf, Anda tidak lulus dalam ujian. Anda tidak bisa mencetak Sertifikat <br>
                    <b>Standar Kelulusan : <?php echo $course->passing_standards ?><br>
                    Skor Anda : <?php echo $examscore->getScore() ?></b><br>

                </div>
            <?php endif ?>
            
        <?php else: ?>

                <div class="alert alert-warning">Anda belum menyelesaikan exam.</div>

        <?php endif ?> 
        

    <?php endforeach ?>

</div>
<!-- END SERTIFIKAT -->


<!-- MODAL EXAM SCORES -->
<div class="modal fade" id="exam-scores-learner" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Skor Exam</h4>
      </div>
      <div class="modal-body">
            <div class="response-data"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
     </div>
    </div>
  </div>
</div>
<!-- END EXAM SCORES MODAL -->


<?php custom_script() ?>
<script type="text/javascript">
    var scores=function(){

        var url     = "<?php echo site_url('course/') ?>";
         
        return{
            init:function(){
                scores.setData();
                
            },
            setData:function(){
                
               
                $('.btn-exam-score-learner').click(function(){
                   
                    var courseid = $(this).attr('course-id');
                  
                    $.ajax({
                        type: "GET",
                        url: url+'/examscores/'+courseid,
                        success: function(response){
                            
                            $('.response-data').html(response);

                        }
                    });
                });
               
            },

        } 
        }();
        scores.init();


 </script>

 <?php endcustom_script() ?>
