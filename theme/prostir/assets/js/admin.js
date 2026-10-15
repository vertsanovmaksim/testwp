/* Адмінка: вибір кількох фото для галереї (wp.media) + сортування. */
(function ($) {
	$(function () {
		$('.prostir-gallery').each(function () {
			var $box = $(this), $input = $box.find('input[type=hidden]'), $grid = $box.find('.prostir-gallery__grid'), frame;
			function sync() {
				var ids = [];
				$grid.find('.prostir-gallery__item').each(function () { ids.push($(this).data('id')); });
				$input.val(ids.join(','));
			}
			$grid.sortable({ update: sync });
			$box.on('click', '.prostir-gallery__remove', function () { $(this).closest('.prostir-gallery__item').remove(); sync(); });
			$box.on('click', '.prostir-gallery__add', function (e) {
				e.preventDefault();
				if (frame) { frame.open(); return; }
				frame = wp.media({ title: 'Оберіть фото', button: { text: 'Додати до галереї' }, library: { type: 'image' }, multiple: 'add' });
				frame.on('select', function () {
					frame.state().get('selection').each(function (att) {
						var a = att.toJSON(), src = (a.sizes && (a.sizes.thumbnail || a.sizes.medium) ? (a.sizes.thumbnail || a.sizes.medium).url : a.url);
						if ($grid.find('[data-id="' + a.id + '"]').length) { return; }
						$grid.append('<span class="prostir-gallery__item" data-id="' + a.id + '"><img src="' + src + '" alt=""><button type="button" class="prostir-gallery__remove" aria-label="Прибрати">×</button></span>');
					});
					sync();
				});
				frame.open();
			});
		});
	});
})(jQuery);
