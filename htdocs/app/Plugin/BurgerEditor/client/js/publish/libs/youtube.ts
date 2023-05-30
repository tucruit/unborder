export function youtube(el: HTMLElement) {
	const videoId = el.getAttribute('data-id');
	if (videoId) {
		new YouTube(el);
	}
}

class YouTube {
	public static PLAYER_URL = '//www.youtube.com/embed/';
	public static API_URL = '//www.youtube.com/player_api';

	/**
	 * YouTubeのiframeのソースURIを生成する
	 *
	 * @version 0.9.1
	 * @since 0.9.1
	 */
	public static getURI(movieId: string, param: Record<string, string | number | boolean>) {
		const paramQuery = $.param(param);
		const apiScheme = /https?:/i.test(location.protocol) ? '' : 'http:';
		return `${apiScheme}${YouTube.PLAYER_URL}${movieId}?${paramQuery}`;
	}

	public movieId: string;
	public player: YT.Player | null = null;
	public isEmbeded = false;
	public src: string;
	public playerDomId: string;
	public title: string;

	/**
	 * コンストラクタ
	 *
	 */
	constructor(el: HTMLElement) {
		const $el = $(el);

		this.movieId = $el.data('id');
		this.title = $el.data('title') || 'YouTube動画';

		const param = {
			version: 3,
			rel: 0,
			autoplay: 0,
			controls: 1,
			disablekb: 1,
			iv_load_policy: 3,
			loop: 0,
			modestbranding: 1,
			showinfo: 1,
			wmode: 'transparent',
			enablejsapi: 1,
		};

		this.src = YouTube.getURI(this.movieId, param);
		this.playerDomId = this.movieId + '-Player';

		this._createPlayerFrame(el);
		this._loadYouTubeAPI();
	}

	private _createPlayerFrame(el: HTMLElement): void {
		const $frame = $('<iframe class="-bc-youtube-player-frame-element" loading="lazy" allowfullscreen />');
		$frame.attr('title', this.title);
		$frame.prop({
			src: this.src,
			id: this.playerDomId,
		});

		$(el).empty();
		$frame.prependTo(el);
	}

	private _loadYouTubeAPI(): void {
		if (!('YT' in window && YT.Player)) {
			const apiScheme = /https?:/i.test(location.protocol) ? '' : 'http:';
			$.getScript(`${apiScheme}${YouTube.API_URL}`);
		}
		const intervalTimer = setInterval(() => {
			if (!this.player && 'YT' in window && YT.Player) {
				this._createPlayer(this.playerDomId);
			}
			if (this.player && this.player.pauseVideo && this.player.playVideo) {
				clearInterval(intervalTimer);
				this._onEmbeded();
			}
		}, 300);
	}

	private _createPlayer(playerID: string): void {
		this.player = new YT.Player(playerID, {
			videoId: this.movieId,
		});
	}

	private _onEmbeded(): void {
		this.isEmbeded = true;
	}
}
