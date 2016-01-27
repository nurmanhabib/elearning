<div class="modal fade add-question" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myLargeModalLabel">Add Question</h4>
            </div>
            <div class="modal-body">
                <form action="" id="form-question-modal">
                    <fieldset class="form-group">
                        <label for="">Pertanyaan :</label>
                        <textarea name="question" class="editor-question required" id="editor-question" cols="30" rows="10"></textarea>
                    </fieldset>
                    <div class="row">
                        <div class="col-sm-12">
                            <label for="">Jawaban :</label>
                            <div class="row">
                                <div class="col-xs-6 col-sm-6">
                                    <fieldset class="form-group">
                                        <div class="input-group">
                                            <div class="input-group-addon">A</div>
                                            <input type="text" class="form-control required" name="answer_a" id="answer_a" v-model="input.chapterQuiz.option_a">
                                        </div>
                                    </fieldset>
                                    <fieldset class="form-group">
                                        <div class="input-group">
                                            <div class="input-group-addon">B</div>
                                            <input type="text" class="form-control required" name="answer_b" id="answer_b" v-model="input.chapterQuiz.option_b">
                                        </div>
                                    </fieldset>
                                </div>
                                <div class="col-xs-6 col-sm-6">
                                    <fieldset class="form-group">
                                        <div class="input-group">
                                            <div class="input-group-addon">C</div>
                                            <input type="text" class="form-control required" name="answer_c" id="answer_c" v-model="input.chapterQuiz.option_c">
                                        </div>
                                    </fieldset>
                                    <fieldset class="form-group">
                                        <div class="input-group">
                                            <div class="input-group-addon">D</div>
                                            <input type="text" class="form-control required" name="answer_d" id="answer_d" v-model="input.chapterQuiz.option_d">
                                        </div>
                                    </fieldset>
                                </div>
                            </div>
                        </div>                            
                        <div class="col-sm-12">
                            <label for="">Jawaban Benar :</label>
                            <div class="row">
                                <div class="col-xs-4 col-sm-4">
                                    <fieldset class="form-group">
                                        <select class="form-control required" name="correct" v-model="input.chapterQuiz.correct">
                                            <option value="a">A</option>
                                            <option value="b">B</option>
                                            <option value="c">C</option>
                                            <option value="d">D</option>
                                        </select>
                                    </fieldset>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary btn-sm" v-on:click="saveChapterQuiz($event)">Save</button>
            </div>
        </div>
    </div>
</div>