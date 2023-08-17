@extends('layouts.app')

@section('title', 'MercjaPlayer')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-dark-subtle">{{ __('Player') }}</div>

                <div class="card-body bg-dark-subtle">
					<div id="episodeButtons">
						<button id="previousButton" class="btn btn-primary btn-dark" onclick="loadPreviousEpisode()">Poprzedni odcinek</button>
						<button id="nextButton" class="btn btn-primary btn-dark" onclick="loadNextEpisode()">Następny odcinek</button>
					</div>

					<div id="epselect">
						<select id="episodeSelect" class="select2" onchange="changeEpisode()">
						@foreach ($episodes as $episode)
						<option value="{{ $episode->url }}">{{ $episode->name }}</option>
						@endforeach
						</select>
					</div>

					<div id="video">
						<iframe id="videoFrame" src="{{ $episodes[0]->url }}" width="640" height="406" allowfullscreen></iframe>
					</div>

					<div id="contentplayer">
						<p>Odtwarzacz nie ładuje się? Kliknij przycisk poniżej.</p>
					</div>

					<div id="linkDiv">
						<a id="linkButton" class="btn btn-primary btn-dark" href="{{ $episodes[0]->url }}" target="_blank">Przejdź do odcinka</a>
					</div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
	var episodes = <?php echo json_encode($episodes); ?>;
	var currentEpisode = 0;
	var videoFrame = document.getElementById("videoFrame");
	var episodeSelect = document.getElementById("episodeSelect");
	var linkButton = document.getElementById("linkButton");
	var $j = jQuery.noConflict();

	function loadPreviousEpisode() {
		if (currentEpisode > 0) {
			currentEpisode--;
			updateVideoFrame();
			updateEpisodeSelect();
			updateLinkButton();
		}
	}

	function loadNextEpisode() {
		if (currentEpisode < episodes.length - 1) {
			currentEpisode++;
			updateVideoFrame();
			updateEpisodeSelect();
			updateLinkButton();
			updateSelect2(); // Dodane wywołanie funkcji aktualizującej Select2
		}
	}

	function updateSelect2() {
		$j('#episodeSelect').val(episodes[currentEpisode].url).trigger('change');
	}

	function changeEpisode() {
		currentEpisode = episodeSelect.selectedIndex;
		updateVideoFrame();
		updateLinkButton();
	}

	function updateVideoFrame() {
		var episode = episodes[currentEpisode];
		videoFrame.src = episode.url;
	}

	function updateEpisodeSelect() {
		episodeSelect.selectedIndex = currentEpisode;
	}

	function updateLinkButton() {
		var episode = episodes[currentEpisode];
		linkButton.href = episode.url;
	}

	$j(document).ready(function() {
		$j('#episodeSelect').select2();
	});
</script>
@endsection