import * as BgE from '../../BgE';
import { IFileUploaderResponse, IUploadFileInfo } from './FileUploader';
import EditorComponent from './EditorComponent';
import TypeEditorDialog from '../editorDialog/TypeEditorDialog';
import Util from '../Util';

/**
 * アップロードファイルリスト要素
 */
export default class UploadFileList extends EditorComponent {
	/**
	 * ファイルリストを取得するリクエストURL
	 */
	public listURL: string | null = BgE.config.api ? BgE.config.api.fileList : null;

	/**
	 * ファイルリストの1ページの件数
	 */
	public pageSplitCount = 10;

	/**
	 * 最大ページ数
	 */
	public pageMaxNumber = 0;

	/**
	 * 現在のページ番号
	 */
	public currentPageNo = 1;

	/**
	 * コンストラクタ
	 *
	 * @param el コンポーネントの要素
	 * @param module コンポーネントが使用されるタイプモジュール
	 *
	 */
	constructor(el: HTMLElement, editorDialog: TypeEditorDialog) {
		super(el, editorDialog);
		const $editorDialog = editorDialog.$el;
		BgE.componentObserver.on('bge-file-upload-complete', this._uploadComplete, this);
		BgE.componentObserver.on('bge-file-listup', this._listup, this);
		// 画像要素にイベントを登録（イベント移譲）
		$editorDialog.on('click', '.bg-upload-file', e => this._fileClick(e));
		$editorDialog.on('dblclick', '.bg-upload-file', e => this._fileDbClick(e));
		$editorDialog.on('click', '[data-bge-pagelink]', e => {
			// ページネーションボタンにページ切り替えイベントを登録
			const $this = $(e.currentTarget);
			const targetPage = parseFloat($this.attr('data-bge-pagelink') || '') || 0;
			this._changePageTab(targetPage);
			return false;
		});
		$editorDialog.on('click', '.page-ctrl button', e => {
			// ページネーションボタンにページ切り替えイベントを登録
			const $this = $(e.currentTarget);
			const vector = parseFloat($this.attr('data-bge-page-vector') || '') || 0;
			this._changePageTab(this.currentPageNo + vector);
			return false;
		});
	}

	/**
	 * 初期化が終わったあとに処理を実行
	 */
	public async afterInit() {
		// 画像をロードしてリストアップ
		await this._loadList('');
	}

	protected _fileClick(e: JQuery.MouseEventBase) {
		const $this = $(e.currentTarget);
		const src = $this.attr('data-org-src') || '';
		const fileSize = parseInt($this.attr('data-file-size') || '', 10) || 0;
		const isEmpty = $this.attr('data-bge-empty') === '1';
		this._select(src, fileSize, isEmpty);
		// selectedクラス切り替え
		this.editorDialog.$el.find('.bg-upload-file').removeClass('selected');
		$this.addClass('selected');
		BgE.componentObserver.notify('bge-file-select', { path: src, isEmpty });
		return false;
	}

	protected _fileDbClick(e: JQuery.MouseEventBase) {
		this._fileClick(e);
		// 完了ボタンクリックを発火
		$('.ui-dialog-buttonset button.last').trigger('click');
		return false;
	}

	/**
	 * ファイルを選択する
	 */
	protected _select(path: string, fileSize: number, isEmpty: boolean) {
		const $editorDialog = this.editorDialog.$el;
		$editorDialog.find('[name=bge-path]').val(path);
		$editorDialog.find('[name="bge-formated-size"]').val(Util.formatByteSize(fileSize));
		$editorDialog.find('[name="bge-size"]').val(`${fileSize}`);
		$editorDialog.find('[name="bge-empty"]').val(isEmpty ? '1' : '0');
	}

	/**
	 * ページネーションのタブを切り替える
	 *
	 * @param pageNo ページ番号
	 *
	 */
	protected _changePageTab(distPageNo: number) {
		// パジネーション
		$('#ContentsEditArea .pagination').remove();
		if (this.pageMaxNumber > 1) {
			const paginationHtml = this._generatePagination(distPageNo);
			this.$el.before(paginationHtml);
		}
		$('[data-bge-pagelink] a').removeClass('current');
		$(`[data-bge-pagelink=${distPageNo}] a`).addClass('current');
		$('[data-bge-page]').hide();
		const $currentPage = $(`[data-bge-page=${distPageNo}]`);
		const $selected = $currentPage.find('.bg-upload-file.selected');
		$currentPage.show();
		if ($selected.length) {
			// $.fn.position メソッドの結果を正確にするためスクロール位置をリセット
			this.$el[0].scrollTop = 0;
			// スクロール位置を移動
			this.$el[0].scrollTop = $selected.position().top - 50;
		}
		this.currentPageNo = distPageNo;
	}

	/**
	 * リストをロードする
	 *
	 * @param filterWord フィルタリングキーワード
	 *
	 */
	private _loadList(filterWord: string = '') {
		return new Promise((resolve, reject) => {
			if (this.listURL) {
				// TODO: タイムアウト処理を書く
				$.ajax(this.listURL, {
					cache: false,
					type: 'GET',
					data: {
						q: filterWord,
					},
					dataType: 'json',
					success: (res: IFileUploaderResponse) => {
						this._render(res.data);
						resolve();
					},
				});
			} else {
				// eslint-disable-next-line no-console
				console.warn('ファイルリストAPIのパスが不明です。');
				reject();
			}
		});
	}

	/**
	 * アップロード完了時の処理
	 *
	 * オブザーバからアップロード完了の通知を受けた時に
	 * リストにファイルを追加する
	 *
	 * TODO: 選択中のファイルパスを登録する要素をコンポーネント化 オブザーバ経由でファイルパスをやりとりする
	 *
	 * @param fileList ファイル情報リスト
	 *
	 */
	private _uploadComplete(fileList: IUploadFileInfo[]) {
		// 直前のアップロードデータ
		let info: IUploadFileInfo;
		if (fileList[0].fileid === '' && fileList[0].size === 0) {
			info = fileList[1];
		} else {
			info = fileList[0];
		}
		// hidden要素に登録する
		this._select(info.url, info.size, false);
		// 結果を描画
		this._render(fileList);
	}

	/**
	 * ファイルリストを取得する
	 *
	 * @param searchWord オブザーバから通知された検索キーワード
	 *
	 */
	private _listup(searchWord: string = '') {
		this._loadList(searchWord);
	}

	/**
	 * 取得したリストを出力する
	 *
	 * @param fileList ファイル情報リスト
	 *
	 */
	private _render(fileList: IUploadFileInfo[]) {
		const $editorArea = this.$el.parents('#ContentsEditArea');
		const $inputTarget = $editorArea.find('[name=bge-path]');
		// DOMをリセット
		this.$el.children().remove();

		// ページング処理
		this.pageMaxNumber = Math.ceil(fileList.length / this.pageSplitCount);
		const fileListHtml: string[] = [];
		$.each(fileList, (i, fileInfo) => {
			if (i % this.pageSplitCount === 0) {
				const pageNumber = Math.floor(i / this.pageSplitCount) + 1;
				fileListHtml.push(`<div data-bge-page="${pageNumber}" style="display:none;">`);
			}
			const isEmpty = i === 0 && fileInfo.fileid === '' && fileInfo.size === 0;
			fileListHtml.push(
				`<div class="bg-upload-file" data-org-src="${fileInfo.url}" data-file-size="${
					fileInfo.size
				}" data-bge-empty="${isEmpty ? '1' : '0'}">
					<div class="file-box ${isEmpty ? 'file-box--no-image' : ''}" data-org-src="${fileInfo.url}"></div>
					<div class="file-info">
						<p><span class="file-info__label">ID</span><span class="bg-upload-id">${fileInfo.fileid}</span></p>
						<p><span class="file-info__label">名称</span><span class="bg-upload-filename" style="word-wrap: break-word;">${
							fileInfo.name
						}</span></p>
						<p><span class="file-info__label">更新</span>${fileInfo.filetime}</p>
						<p><span class="file-info__label">サイズ</span>${Util.formatByteSize(fileInfo.size)}</p>
					</div>
				</div>`,
			);
			if ((i + 1) % this.pageSplitCount === 0) {
				fileListHtml.push('</div>');
			}
		});
		this.$el.append(fileListHtml.join(''));

		this.$el.find('.bg-upload-file').removeClass('selected');

		// 選択されている画像のページ
		const viewSrc = $inputTarget.val();
		let targetPage = 1;
		this.$el.find('.bg-upload-file .file-box').each((i, el) => {
			if ($(el).attr('data-org-src') === viewSrc) {
				targetPage = parseFloat($(el).parents('[data-bge-page]').attr('data-bge-page') || '') || 1;
				$(el).parents('.bg-upload-file').addClass('selected');
			}
		});
		// 画像を描画
		this.$el.find('.file-box[data-org-src]').each((i, el) => {
			const $this = $(el);
			const src = $this.attr('data-org-src') || '';
			if (!$this.hasClass('file-box--no-image')) {
				$this.css('background-image', `url("${encodeURI(src)}")`);
			}
		});
		// 選択されている画像のあるページに切り替え
		this._changePageTab(targetPage);
	}

	private _generatePagination(curentPageNo: number) {
		const paginationHtml: string[] = [];
		paginationHtml.push(`
			<div
				class="pagination"
				data-bge-page-index="${curentPageNo - 1}"
				data-bge-page-last-index="${this.pageMaxNumber - curentPageNo}"
				data-bge-page-max="${this.pageMaxNumber}">`);
		paginationHtml.push(`
			<div class="page-ctrl page-ctrl--prev"}>
				<button ${curentPageNo === 1 ? 'disabled' : ''} type="button" data-bge-page-vector="-1">前</button>
			</div>`);
		paginationHtml.push('<div class="page-numbers">');
		for (let i = 1; i <= this.pageMaxNumber; i++) {
			paginationHtml.push(`
				<button
					type="button"
					class="number"
					data-bge-current="${+(curentPageNo === i)}"
					data-bge-page-dist="${Math.abs(curentPageNo - i)}"
					data-bge-pagelink="${i}"
					data-bge-page-index="${i - 1}"
					data-bge-page-last-index="${this.pageMaxNumber - i}"
				>
					${i}
				</button>`);
		}
		paginationHtml.push('</div>');
		paginationHtml.push(`
			<div class="page-ctrl page-ctrl--next">
				<button ${curentPageNo === this.pageMaxNumber ? 'disabled' : ''} type="button" data-bge-page-vector="1">次</button>
			</div>`);
		paginationHtml.push('</div>');
		return paginationHtml.join('').replace(/\t/g, '');
	}
}
