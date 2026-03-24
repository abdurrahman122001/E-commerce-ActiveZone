<div class="modal fade confirmRejectModal" id="reject-modal" tabindex="-1"
    aria-labelledby="confirmRejectModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-corner-8px">
            <div class="d-flex justify-content-end py-15px px-15px">
                <button type="button" class="border-0 bg-transparent" data-dismiss="modal">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                        viewBox="0 0 49.814 49.814">
                        <path id="Path_45225" data-name="Path 45225"
                            d="M239.647-672.186,238-673.833l23.26-23.26L238-720.353,239.647-722l23.26,23.26L286.167-722l1.647,1.647-23.26,23.26,10.358,10.358,12.9,12.9-1.647,1.647-23.26-23.26Z"
                            transform="translate(-238 722)" fill="#6c757d" />
                    </svg>

                </button>
            </div>
            <div class="modal-body pt-3 pb-30px px-3 px-lg-5">
                <div class="text-center pb-4">
                    <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100"
                            viewBox="0 0 100 100">
                            <g id="Group_38895" data-name="Group 38895" transform="translate(-1316 -1566)">
                                <g id="Ellipse_1036" data-name="Ellipse 1036"
                                    transform="translate(1316 1566)" fill="#fff" stroke="#ffc107"
                                    stroke-width="2">
                                    <circle cx="50" cy="50" r="50" stroke="none" />
                                    <circle cx="50" cy="50" r="49" fill="none" />
                                </g>
                                <text transform="translate(1366 1630)" fill="#ffc107" font-size="60" font-family="PublicSans-Bold, Public Sans" font-weight="700"><tspan x="-15" y="0">!</tspan></text>
                            </g>
                        </svg>
                    </span>
                    <h5 class="m-0 text-uppercase text-dark fs-24 fw-700 mt-4 pt-2">{{translate('Confirmation')}}</h5>
                </div>
                <div>
                    <p class="fs-14 fw-200 text-dark text-center">{{translate('Are you sure you want to')}} <span
                            class="fw-700 text-danger">{{translate('Reject')}}</span> {{translate('this request?')}}</p>
                </div>
            </div>
            <div class="modal-footer justify-content-center border-top-0">
                <button type="button" class="btn btn-secondary px-4 mr-2" data-dismiss="modal">{{translate('No')}}</button>
                <a href="" id="reject-link" class="btn btn-danger px-4">{{translate('Yes, Reject')}}</a>
            </div>
        </div>
    </div>
</div>
