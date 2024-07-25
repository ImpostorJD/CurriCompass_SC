import { Component, EventEmitter, Output } from '@angular/core';
import { FormBuilder, FormGroup, ReactiveFormsModule, Validators } from '@angular/forms';
import { HttpReqHandlerService } from '../../services/http-req-handler.service';
import { AuthService } from '../../services/auth/auth.service';
import { httpOptions } from '../../../configs/Constants';
import { saveAs } from 'file-saver';

@Component({
  selector: 'app-student-bulk-registration-modal',
  standalone: true,

  templateUrl: './student-bulk-registration-modal.component.html',
  styleUrls: ['./student-bulk-registration-modal.component.css'],
  imports: [
    ReactiveFormsModule
  ],
})

export class StudentBulkRegistrationModalComponent {
  @Output() collapseModal = new EventEmitter<boolean>();
  @Output() refetch = new EventEmitter<boolean>();
  selectedFile: File | null = null;

  constructor(private fb: FormBuilder, private req: HttpReqHandlerService, private auth: AuthService) {

  }
 fileForm = this.fb.group({
    fileInput: [null, Validators.required]
  });

  collapseModalEvent() {
    this.collapseModal.emit(true);
  }


  refetchStudent() {
    this.refetch.emit(true);
  }

  onFileSelected(event: any) {
    const file = event.target.files[0];
    if (file && file.type === 'text/csv') {
      this.selectedFile = file;
      this.fileForm.patchValue({
        fileInput: file.name // Just set the file name to the form control, not the file object itself
      });
    } else {
      alert('Please select a valid CSV file.');
    }
  }

  downloadFile(){
    this.req.getResource('sample-csv', { responseType: 'blob' as 'json' })
    .subscribe({
      next: (data: any) => {
        saveAs(data, 'sample.csv');
      },
      error: (error: any) => {
        console.error('Error file:', error);
      }
    });
  }

  onSubmit() {
    if (this.fileForm.valid && this.selectedFile) {
      const formData = new FormData();
      formData.append('file', this.selectedFile, this.selectedFile.name);

      console.log('File submitted:', this.selectedFile);
      this.req.postResource('student-records/bulk', formData, httpOptions(this.auth.getCookie('user'), true))
        .subscribe({
          next: (data: any) => {
            this.collapseModalEvent();
            this.refetchStudent();
          },
          error: (error: any) => {
            console.error('Error uploading file:', error);
          }
        });
    } else {
      alert('Please select a file before submitting.');
    }
  }
}
