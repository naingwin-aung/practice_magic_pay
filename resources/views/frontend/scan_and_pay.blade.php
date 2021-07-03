@extends('frontend.layouts.app')
@section('title', "Scan & Pay")

@section('content')
    <div class="scan__pay">
        <div class="card">
            <div class="card-body text-center">
                
                @include('frontend.layouts.flash')

                <div>
                    <img src="{{asset('/img/qr_code_scan.png')}}" alt="QR Scan" width="210px">
                </div>
                <p class="mb-0">Click button.</p>
                <p>Put QR code in the frame and pay.</p>
                <button class="btn btn-primary" type="submit" data-toggle="modal" data-target="#scanModal">Scan</button>
                
                <!-- Scan Modal -->
                <div class="modal fade" id="scanModal" tabindex="-1" aria-labelledby="scanModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="scanModalLabel">Scan & Pay</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <video id="scanner" width="100%" height="240px"></video>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{asset('frontend/js/instascan.min.js')}}"></script>
    {{-- <script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script> --}}
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.1.10/vue.min.js"></script> --}}
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/webrtc-adapter/3.3.3/adapter.min.js"></script> --}}
    <script>
        $(document).ready(function() {
            let scanner = new Instascan.Scanner({video: document.getElementById('scanner')});

            scanner.addListener('scan', function(result) {
                scanner.stop();
                $('#scanModal').modal('hide');
                let to_phone = result;
                window.location.replace(`/scan-and-pay-form?to_phone=${to_phone}`)
            })

            Instascan.Camera.getCameras()
                .then(function(cameras) {
                    if(cameras.length > 0) {
                        $('#scanModal').on('show.bs.modal', function(e) {
                            scanner.start(cameras[0]);
                        })
                    } else {
                        console.error('No cameras found.');
                    }
                })
                .catch(function(e) {
                    console.error(e);
                });

            $('#scanModal').on('hidden.bs.modal', function(e) {
                scanner.stop();
            })

        })
    </script>
@endsection