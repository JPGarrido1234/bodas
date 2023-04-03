<html>
  <head>
    <meta charset="utf-8" />
    <script src="https://unpkg.com/pdf-lib@1.11.0"></script>
    <script src="https://unpkg.com/downloadjs@1.4.7"></script>
  </head>

  <body>
    <button onclick="fillForm()">Descargar</button>
  </body>

  <script>
    const { PDFDocument } = PDFLib

    async function fillForm() {
    	// Fetch the PDF with form fields
      const formUrl = '/pdf-example.pdf'
      const formPdfBytes = await fetch(formUrl).then(res => res.arrayBuffer())

      // Load a PDF with form fields
      const pdfDoc = await PDFDocument.load(formPdfBytes)

      // Get the form containing all the fields
      const form = pdfDoc.getForm()

      // Get all fields in the PDF by their names
      const nameField = form.getTextField('Given Name Text Box')

      // Fill in the basic info fields
      nameField.setText('Benji')

      // Serialize the PDFDocument to bytes (a Uint8Array)
      const pdfBytes = await pdfDoc.save()

	  // Trigger the browser to download the PDF document
      download(pdfBytes, "pdf-lib_form_creation_example.pdf", "application/pdf");
    }
  </script>
</html>