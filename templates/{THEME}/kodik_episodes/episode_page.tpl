<div class="episode-page">
	[seasons-links]<div class="episode-seasons">{seasons-links}</div>[/seasons-links]
	[episode-player]<div class="episode-playlist">{episode-player}</div>[/episode-player]
	[episodes-links]<div class="episode-links">{episodes-links}</div>[/episodes-links]
	<div class="episode-story">{full-story}</div>
</div>
<style>
.episode-page {
    display: flex;
    flex-direction: column;
    margin: 30px 0;
    padding: 30px 0;
}

.episode-links, .episode-seasons {
    display: flex;
    flex-wrap: wrap;
    margin: 10px;
}

.episode-links a {
    display: flex!important;
}
</style>