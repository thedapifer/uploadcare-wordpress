$j = uploadcare.jQuery;

$j(document).ready(function() {
    $j('#uploadcare_checkout_field').hide();
    var widget = uploadcare.MultipleWidget('#uploadcare_checkout_field');
    widget.onUploadComplete(function(groupInfo) {
        var preview = $j('#uploadcare_checkout_field_preview');
        preview.html('');
        uploadcare.loadFileGroup(groupInfo.uuid).done(function(group) {
            var files = group.files();
            for(var idx = 0; idx < files.length; idx++) {
                var file = files[idx];
                file.done(function(fileInfo) {
                    var img = $j('<img>');
                    img.attr('src', fileInfo.cdnUrl + '-/preview/200x200/');
                    preview.append(img);
                });
            }
        });
    });
});
