<script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>
						<div class="control-group"><div class="controls">
    <video id="video" controls></video>
    <script>
    if(Hls.isSupported())
    {
        var video = document.getElementById('video');
		var config = {
debug: true,
xhrSetup: function (xhr,url) {
xhr.withCredentials = true; // do send cookie
xhr.setRequestHeader("Access-Control-Allow-Headers","Content-Type, Accept, X-Requested-With");
    xhr.setRequestHeader("Access-Control-Allow-Origin","http://hktest.ulifestyle.com.hk/cms");
xhr.setRequestHeader("Access-Control-Allow-Credentials","true");
}
};
//var link='http://hket03.video003.s3.ap-southeast-1.amazonaws.com/p1/hls/13df3f98ba231cb65626047b40b9bf43.m3u8';
var link='https://s3.ap-southeast-1.amazonaws.com/hket03.video003/p1/hls/13df3f98ba231cb65626047b40b9bf43.m3u8';
        var hls = new Hls();
        hls.loadSource(link);
        hls.attachMedia(video);
        hls.on(Hls.Events.MANIFEST_PARSED,function()
        {
            video.play();
        });
    }
    else if (video.canPlayType('application/vnd.apple.mpegurl'))
    {
        video.src = link;
        video.addEventListener('canplay',function()
        {
            video.play();
        });
    }
    </script>
					</div></div>