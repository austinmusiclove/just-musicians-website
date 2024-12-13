class CroppableImageInput {
    constructor(imageInput, croppedImageInput, imageDisplay) {
        this.cropper = null;
        this.imageInput = imageInput;
        this.croppedImageInput = croppedImageInput;
        this.imageDisplay = imageDisplay;
        this._setupListeners()
    }
    _setupListeners() {
        this.imageInput.addEventListener('change', this.handleImageChange.bind(this));
    }

    handleImageChange(evnt) {
        const files = evnt.target.files;
        if (files && files.length > 0) {
            const reader = new FileReader();
            reader.onload = function (e) {
                this.imageDisplay.src = e.target.result;
                this.imageDisplay.style.display = "block";

                if (this.cropper) this.cropper.destroy(); // Destroy existing cropper if it exists

                // Initialize Cropper.js with 4:3 aspect ratio
                this.cropper = new Cropper(this.imageDisplay, {
                    aspectRatio: 4 / 3,
                    viewMode: 1,
                    cropend: this.handleCropEnd.bind(this),
                });
            }.bind(this);

            reader.readAsDataURL(files[0]);
        }
    }

    // Set the cropped image as the value of the cropped file input
    handleCropEnd() {
        if (this.cropper) {
            const croppedCanvas = this.cropper.getCroppedCanvas();

            croppedCanvas.toBlob((blob) => {
                if (blob) {
                    const file = new File([blob], "cropped-image.png", { type: "image/png" });
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(file);
                    this.croppedImageInput.files = dataTransfer.files;
                }
            }, "image/png");
        }
    }

}

export default CroppableImageInput;
