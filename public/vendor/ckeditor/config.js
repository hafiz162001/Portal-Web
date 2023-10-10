/**
 * @license Copyright (c) 2003-2022, CKSource Holding sp. z o.o. All rights reserved.
 * For licensing, see https://ckeditor.com/legal/ckeditor-oss-license
 */

CKEDITOR.editorConfig = function (config) {
    config.toolbarGroups = [
        { name: "document", groups: ["mode", "document", "doctools"] },
        { name: "clipboard", groups: ["clipboard", "undo"] },
        {
            name: "editing",
            groups: ["find", "selection", "spellchecker", "editing"],
        },
        { name: "forms", groups: ["forms"] },
        { name: "links", groups: ["links"] },
        { name: "insert", groups: ["insert"] },
        { name: "tools", groups: ["tools"] },
        "/",
        { name: "basicstyles", groups: ["basicstyles", "cleanup"] },
        {
            name: "paragraph",
            groups: ["list", "indent", "blocks", "align", "bidi", "paragraph"],
        },
        { name: "styles", groups: ["styles"] },
        { name: "colors", groups: ["colors"] },
        { name: "others", groups: ["others"] },
        { name: "about", groups: ["about"] },
    ];

    config.removeButtons =
        "Source,Save,NewPage,Print,Preview,ExportPdf,Templates,Cut,Paste,PasteFromWord,PasteText,Copy,Replace,Scayt,Form,Checkbox,Textarea,Select,Button,ImageButton,HiddenField,TextField,Radio,CopyFormatting,CreateDiv,Language,Anchor,Image,HorizontalRule,Smiley,PageBreak,Iframe,Font,BGColor,ShowBlocks,About,TextColor";
};
