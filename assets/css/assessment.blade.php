@include('layouts.head')

<body>
    <a id="button"></a>
    @include('layouts.header')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="ntmr_container">
                    <div class="row">
                        <div class="col-sm-12 pt-4">
                            <a class="link" style="font-size: 28px;" href="javascript:;">
                                <i class="fa-solid fa-arrow-left pe-2"></i>Administrative Reform

                            </a>
                            <h5 class="abs-medium mb-2 pt-4">Progress</h5>
                        </div>
                        <div class="col-sm-12">
                            <div class="d_flx">
                                @php
                                    $catCount = 1;
                                @endphp
                                @foreach($categories as $category)
                                    <div class="{{ ($category->id == 1)? 'text-primary' : 'text-secondary'}}  abs-medium" id="cat{{$category->id}}">
                                        
                                        <svg class="svg-inline--fa fa-circle-check pe-2 reform-svg-{{$category->id}}" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="circle-check" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="" @if($category->id > 1) style="display:none" @endif><path fill="currentColor" d="M256 512c141.4 0 256-114.6 256-256S397.4 0 256 0S0 114.6 0 256S114.6 512 256 512zM369 209L241 337c-9.4 9.4-24.6 9.4-33.9 0l-64-64c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l47 47L335 175c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9z" ></path></svg>
                                        <i class="bi bi-{{$category->id}}-circle-fill pe-2 reform-icon-{{$category->id}}" @if($category->id == 1) style="display:none" @endif></i>
                                         {{ $category->name}}
                                    </div>  
                                    @if($catCount < count($categories))
                                        @php
                                            $catCount++;
                                        @endphp
                                        <div class="lines"></div>
                                    @endif  
                                @endforeach
                                
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="pt-3 pb-5">
                                <small><span class="progress-bar-text abs-medium">0%</span> completed</small>
                                <div class="progress bg-info-subtle">
                                    <div class="progress-bar"></div>
                                </div>
                            </div>
                        </div>
                        
                        <form method="POST" id="qform" >
                            @csrf
                            @php
                            $count = 0;
                            @endphp
                            @foreach($categories as $key1 => $category)
                                @foreach($questions[$category->id] as $key2 => $reform)
                                <div class="col-sm-12 catdetails @if($count == 0) active @endif" data-catid="{{$category->id}}">  
                                    <div class="col-sm-12 pb-4">
                                        <div class="d_flx">
                                            <h4 class="mb-0">
											<img src="./public/assets//images/graduation-cap_2.png" alt="" style="height: 35px;position: relative;top: -2px;">
											
											{{str_replace('_', ' ', $key2)}}</h4>
                                        </div>
                                        <small class="text-danger abs-medium">Disclaimer: This is different from affiliation or renewal of license.</small>
                                    </div>
                                    @foreach($reform as $key3 => $q)
                                    <div class="card mb-4">
                                            <div class="card-body">

                                                <ol start="{{ $loop->iteration }}">
                                                    <li class="abs-medium card_sm_fcolor">{{ $q->question_title }}@if($q->is_mandatory) <span class="text-danger">*</span> @endif</li>
                                                </ol>
                                                @if($q->question_type == 3)
                                                <ul style="list-style: none;">
                                                    <li>
                                                        <input type="text" name="q[{{$q->category_name}}][{{$q->reform_name}}][{{$q->id}}]" class="form-control" @if($q->is_mandatory == 1) required @endif>
                                                    </li>
                                                </ul>
                                                @else

                                                <ul style="list-style: none;">
                                                    @foreach ($q->options as $opt)
                                                        <li class="mb-1">
                                                            @if($q->question_type == 1)
                                                            <input type="radio"  id="q_{{ $q->id }}_{{ $loop->index }}" name="q[{{$q->category_name}}][{{$q->reform_name}}][{{$q->id}}]"
                                                                value="{{ $opt->weightage }}" @if($q->is_mandatory == 1) required @endif>
                                                            @else
                                                            <input type="checkbox" id="q_{{ $q->id }}_{{ $loop->index }}" name="q[{{$q->category_name}}][{{$q->reform_name}}][{{$q->id}}][]"
                                                                value="{{ $opt->weightage }}" @if($q->is_mandatory == 1) required @endif>
                                                            @endif
                                                            <label class="abs-light" for="q_{{ $q->id }}_{{ $loop->index }}">
                                                                {{ $opt->option_title }}
                                                            </label>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                                @endif

                                            </div>
                                        </div>
                                    @endforeach
                                        </div>
                                        @php
                                $count++;
                                @endphp
                                @endforeach                                            
                            @endforeach
                        </form>
                        <div class="col-sm-12 pb-5 pt-3">
                            <a href="javascript:;" class="btn btn-primary float-end abs-medium plr-100 next py-2" >Next</a>
                            <a href="javascript:;" class="btn btn-primary float-end abs-medium plr-100 prev py-2" style="margin-right:10px;display:none">Prev</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade show" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-modal="true" role="dialog">
                                <div class="modal-dialog modal-dialog-centered modal-sm">
                                    <div class="modal-content">
                                        <div class="modal-body">
                                            <div class="d-flex justify-content-around align-content-center g-2">
                                                <svg class="svg-inline--fa fa-circle-check pe-2 text-success" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="circle-check" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="M256 512c141.4 0 256-114.6 256-256S397.4 0 256 0S0 114.6 0 256S114.6 512 256 512zM369 209L241 337c-9.4 9.4-24.6 9.4-33.9 0l-64-64c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l47 47L335 175c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9z"></path></svg><!-- <i class="fa-solid fa-circle-check pe-2 text-success"></i> Font Awesome fontawesome.com -->
                                                <div class="">
                                                    <p class="mb-0">Congratulations!</p>
                                                    <small class="text-muted">
                                                        You've successfully completed the assessment. Your diagnostic
                                                        score is loading, kindly give us a minute.
                                                    </small>
                                                </div>
                                                <!-- <div class="">
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div> -->
                                            </div>
                                        </div>
                                        <!-- <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Close</button>
                                            <button type="button" class="btn btn-primary">Save changes</button>
                                        </div> -->
                                    </div>
                                </div>
                            </div>
    <!-- FOOTER HTML  -->
    @include('layouts.footer')
</body>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript">
    AOS.init({
        duration: 1000
    });
    var btn = $('#button');

    $(window).scroll(function() {
        if ($(window).scrollTop() > 300) {
            btn.addClass('show');
        } else {
            btn.removeClass('show');
        }
    });

    btn.on('click', function(e) {
        e.preventDefault();
        $('html, body').animate({
            scrollTop: 0
        }, '300');
    });

    function allRadiosSelected($reformId) {
        let allSelected = true;

        /* ---------- RADIO & CHECKBOX (group based) ---------- */
        $('.catdetails.active input[required][type="radio"], .catdetails.active input[required][type="checkbox"]').each(function () {
            const name = $(this).attr('name');

            if ($(`input[name="${name}"]:checked`).length === 0) {
                allSelected = false;
                return false; // break loop
            }
        });

        /* ---------- TEXT / TEXTAREA / NUMBER ---------- */
        if (allSelected) {
            $('.catdetails.active input[required]:not([type="radio"]):not([type="checkbox"]), .catdetails.active textarea[required]').each(function () {
                if ($.trim($(this).val()) === '') {
                    allSelected = false;
                    return false;
                }
            });
        }

        /* ---------- RESULT ---------- */
        if (!allSelected) {
            alert('Please fill all required fields');
            return false;
        }

        return allSelected

    }

    // if (allRadiosSelected()) {
    //     console.log("All selected!");
    // } else {
    //     console.log("Missing selection!");
    // }


    $(function(){
        var catCount = '{{count($categories)}}';
        
        // alert(next);

        $('form').on('submit', function (e) {
            if (!allRadiosSelected()) {
                e.preventDefault();
                Swal.fire({
                            title: 'Error!',
                            text: 'Answer all questions to submit.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
            }
            else
            {
                e.preventDefault();
                let form = this;
                $('#exampleModal').modal('show');
                setTimeout(function () {
                    form.submit();
                }, 1000);
            }
        });

        function togglePrevButton() {
            let isFirstActive = $('.catdetails').first().hasClass('active');

            if (isFirstActive) {
                $('.prev').hide();
            } else {
                $('.prev').show();
            }
        }


        $('.next').on('click', function () {

            let $current = $('.catdetails.active');

            let proceed = allRadiosSelected();
            if(proceed){

                let $next = $current.next('.catdetails');
    
                // if last, go to first
                if ($next.length === 0) {
                    $('#qform').submit();
                }
                else
                {   
    
                    $current.removeClass('active');
                    $next.addClass('active');
    
                    let prevcatid = $current.attr('data-catid');
                    let catid = $next.attr('data-catid');
    
                    if(prevcatid != catid){
                        $('.reform-svg-'+prevcatid).hide();
                        $('.reform-icon-'+prevcatid).show();
                        $('#cat'+prevcatid).removeClass('text-primary').addClass('text-success');
                        $('#cat'+catid).removeClass('text-secondary').addClass('text-primary');
                        $('.reform-svg-'+catid).show();
                        $('.reform-icon-'+catid).hide();
                    }
                    $('html, body').animate({
                        scrollTop: 0
                    }, '600', 'linear');
                }
    
                togglePrevButton();
            }
            


        });

        $('.prev').on('click', function () {

            let $current = $('.catdetails.active');
            let $prev = $current.prev('.catdetails');

            // if last, go to first
            if ($prev.length) 
            {                
                $current.removeClass('active');
                $prev.addClass('active');

                let catid = $current.attr('data-catid');
                let prevcatid = $prev.attr('data-catid');

                if(prevcatid != catid){
                    $('.reform-svg-'+prevcatid).show();
                    $('.reform-icon-'+prevcatid).hide();
                    $('#cat'+catid).removeClass('text-primary').addClass('text-secondary');
                    $('#cat'+prevcatid).removeClass('text-success').addClass('text-primary');
                    $('.reform-svg-'+catid).hide();
                    $('.reform-icon-'+catid).show();
                }
                $('html, body').animate({
                        scrollTop: 0
                    }, '600', 'linear');
            }

            togglePrevButton();


        });
        
        $('input[type="radio"]').click(function(){
            var total = '{{$totalQuestions}}';
            // alert(total);
            var answered = parseInt($("input[type='radio']:checked").length);
            // alert(answered);

            var percentage = parseInt((answered*100)/total);
            $('.progress-bar').css('width', percentage+"%");
            $('.progress-bar-text').text(percentage+"%");

        })
    })
</script>
<style>
    .catdetails{
        display: none;
    }
    .catdetails.active{
        display: block !important;
    }
    </style>

</html>
