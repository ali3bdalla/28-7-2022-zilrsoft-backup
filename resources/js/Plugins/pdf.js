// import VueHtml2pdf from 'vue-html2pdf'

// export default {
//     methods: {
//         /*
//             Generate Report using refs and calling the
//             refs function generatePdf()
//         */
//         generateReport() {
//             // const modal = document.getElementById("modalInvoice")
//             // const cloned = modal.cloneNode(true)
//             // let section = document.getElementById("print")

//             // if (!section) {
//             //     section = document.createElement("div")
//             //     section.id = "print"
//             //     document.body.appendChild(section)
//             // }

//             // section.innerHTML = "";
//             // section.appendChild(cloned);
//             // window.print();
//             var printContents = document.getElementById("pdfLayout").innerHTML;
// 			var originalContents = document.body.innerHTML;

// 			document.body.innerHTML = printContents;

// 			window.print();

// 			document.body.innerHTML = originalContents;

//             // this.$refs.html2Pdf.generatePdf()
//         },
//         async beforeDownload({ html2pdf, options, pdfContent }) {
//             await html2pdf().set(options).from(pdfContent).toPdf().get('pdf').then((pdf) => {
//                 const totalPages = pdf.internal.getNumberOfPages()
//                 for (let i = 1; i <= totalPages; i++) {
//                     pdf.setPage(i)
//                     pdf.setFontSize(10)
//                     pdf.setTextColor(150)
//                     pdf.text('Page ' + i + ' of ' + totalPages, (pdf.internal.pageSize.getWidth() * 0.88), (pdf.internal.pageSize.getHeight() - 0.3))
//                 }
//             }).save()
//         }
//     },

//     components: {
//         VueHtml2pdf
//     }
// }