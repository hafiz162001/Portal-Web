@include('front.header')

<section>
    <div class="container pt-0">
        <div class="row">
            <!-- LEFT SECTION -->
            <div class="col-md-7">
                <h4>{{$article->channel_name}}</h4>
                <h4>{{$article->title}}</h3>
                <div class="row pb-2">
                    <div class="col-4 d-flex align-items-center">
                        <span>{{ $article->creator }}</span>
                    </div>
                    <div class="col-4 d-flex align-items-center">
                        <span>{{ $article->created_at }}</span>
                    </div>
                    <div class="col-4">
                        <div class="social-link rounded style-2 justify-content-start">
                            <a href="https://www.instagram.com/" target="_blank" title="Link"><i class="fab fa-instagram"></i></a>
                            <a href="https://www.dribbble.com/" target="_blank" title="Link"><i class="fab fa-dribbble"></i></a>
                            <a href="https://www.twitter.com/" target="_blank" title="Link"><i class="fab fa-twitter"></i></a>
                            <a href="https://www.youtube.com/" target="_blank" title="Link"><i class="fab fa-youtube"></i></a>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <img
                            src="{{ asset('img/' . $article->gambar) }}"
                            class="rounded"
                        >
                    </div>
                    <div class="col-12 my-2">
                        <small class="text-muted">
                            {{ $article->sumber_gambar }}
                        </small>
                    </div>
                    <div class="col-12 my-3">
                        <div class="row">
                            <div class="col-md-10 my-2">
                                {{-- {!! $article->descriptions !!} --}}
                                @php
                                    $length = count($paragraphs);

                                    $halfLength = ceil($length / 4);
                                    $firstHalf1 = array_slice($paragraphs, 0, $halfLength); // Memotong paragraphs menjadi setengah bagian pertama
                                    $secondHalf1 = array_slice($paragraphs, $halfLength, $halfLength);
                                    $threeHalf1 = array_slice($paragraphs, $halfLength * 2);
                                    $foorHalf1 = array_slice($paragraphs, $halfLength * 3);

                                    $page1 = implode($firstHalf1);
                                    $page2 = implode($secondHalf1);
                                    $page3 = implode($threeHalf1);
                                    $page4 = implode($foorHalf1);
                                @endphp
                                <div class="nah-pada-kamis-container">
                                    <p class="bangga-karya-indonesia">
                                        {!! $page1 !!}
                                    </p>
                                </div>
                            </div>
                            <div class="detail-berita-inner2">
                                <div class="baca-juga">
                                    <div class="nasional">Baca Juga</div>
                                    <div style="">
                                        {{$baca_juga[$rndIndex]['title'] ?? $baca_juga[0]['title']}}
                                    </div>
                                    <a href="/front/detail/{{ $baca_juga[$rndIndex]['slug'] ?? $baca_juga[0]['title']}}" class="selengkapnya">Selengkapnya</a>
                                </div>
                            </div>
                            <div class="badan-yang-penuh-keringat-dari-parent">
                                <div class="badan-yang-penuh">
                                   {!! $page2 !!}
                                </div>
                            </div>
                            <img
                                class="detail-berita-child7"
                                alt=""
                                src="/img/frame-2577@2x.png"
                            />
                            <div class="jadi-keringatnya-kelihatan-je-parent">
                                <div class="badan-yang-penuh">
                                    {!! $page3 !!}
                                </div>
                            </div>
                            <div class="detail-berita-inner3">
                                <div class="baca-juga">
                                    <div class="nasional">Baca Juga</div>
                                    <div style="">
                                        {{$baca_juga[$rndIndex + 1]['title'] ?? $baca_juga[0]['title']}}
                                    </div>
                                    <a href="/front/detail/{{ $baca_juga[$rndIndex + 1]['slug'] ?? $baca_juga[0]['title']}}" class="selengkapnya">Selengkapnya</a>
                                </div>
                            </div>
                            <div class="setelah-melakukan-olah-tempat-parent">
                                <div class="badan-yang-penuh">
                                    {!! $page4 !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RIGHT SECTION -->
            <div class="col-md-5">
                <x-news-category-top
                    :data="$cardArticle10"
                />

                <div class="row">
                    <div class="col-12">
                        {{-- adsense --}}
                        <img src="https://thumbor.prod.vidiocdn.com/ejKN1Uo7yb4BhS6SkXfDSRHR6Sg=/filters:quality(70)/vidio-web-prod-film/uploads/film/image_portrait/3749/jujutsu-kaisen-79952b.jpg" alt="" srcset="">
                    </div>
                </div>
                
                <!-- TAGS -->
                <x-news-list-tag
                    :data="$tags"
                />
            </div>
        </div>
    </div>
</section>
        
<section class="course-area">
    <div class="container">
        <x-news-category-main
            :data="$cardArticle4"
        />
    </div>
</section>

<section>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="row">
                    <div class="col-md-7">
                        <x-news-category-latest-v2
                            :data="$cardArticle8"
                        />
                    </div>
                    <div class="col-md-5">
                        {{-- loop adsense in here --}}
                        <div class="row">
                            @for ($i = 0; $i < 1; $i++)
                            <div class="col-12">
                                <img src="https://thumbor.prod.vidiocdn.com/ejKN1Uo7yb4BhS6SkXfDSRHR6Sg=/filters:quality(70)/vidio-web-prod-film/uploads/film/image_portrait/3749/jujutsu-kaisen-79952b.jpg" alt="" srcset="">
                            </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@include('front.footer')
