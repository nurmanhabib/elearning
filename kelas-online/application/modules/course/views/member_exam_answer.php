<div class="card">
    <div class="card-block">
        <h4 class="card-title"><?php echo $course->code ?> - <?php echo $course->name ?></h4>
        <h6 class="card-subtitle text-muted">Terdiri dari <?php echo $course->exam->questions->count() ?> pertanyaan</h6>
    </div>
    <div class="card-block">
        
        <p>Score Anda:</p>
        <h1><?php echo round($member->getScore(), 2) ?></h1>
    </div>
</div>
<?php $numb = 1; foreach ($answers as $answer): ?>
    <?php
    if ($answer->is_correct) {
        $label  = 'success';
        $text   = 'BENAR';
    } else {
        $label  = 'danger';
        $text   = 'SALAH';
    }
    ?>
    <div class="card">
        <div class="card-block">
            <?php echo $numb ?>. <?php echo strip_tags($answer->question->question) ?>
            <p>Jawaban: <?php echo strtoupper($answer->answer) ?> <span class="label label-<?php echo $label ?>"><?php echo $text ?></span></p>
        </div>
    </div>
<?php $numb++; endforeach ?>