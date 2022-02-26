<div class="col-md-6">
    <div class="border-0 card">
        <div class="card-body">
            <h3 class="card-title">Baidu</h3>
            <form action="./Seo/baidu" method="post">
                <x-csrf/>
                <div class="mb-3">
                    <label for="" class="form-label">网址</label>
                    <input type="url" name="url" value="{{get_options('seo_baidu_url')}}" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="" class="form-label">Token</label>
                    <input type="text" value="{{get_options_nocache('seo_baidu_token')}}" name="token" class="form-control" required>
                </div>
                <button class="btn btn-light">保存</button>
            </form>
        </div>
    </div>
</div>