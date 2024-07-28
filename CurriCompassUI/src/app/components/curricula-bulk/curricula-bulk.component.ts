import { Component, EventEmitter, Output } from '@angular/core';
import { FormBuilder, ReactiveFormsModule, Validators } from '@angular/forms';
import { HttpReqHandlerService } from '../../services/http-req-handler.service';
import { AuthService } from '../../services/auth/auth.service';
import { saveAs } from 'file-saver';
import { httpOptions } from '../../../configs/Constants';

@Component({
  selector: 'app-curricula-bulk',
  standalone: true,
  imports: [ReactiveFormsModule],
  templateUrl: './curricula-bulk.component.html',
  styleUrl: './curricula-bulk.component.css'
})
export class CurriculaBulkComponent {
  @Output() collapseModal = new EventEmitter<boolean>();
  @Output() refetch = new EventEmitter<boolean>();
  selectedFile: File | null = null;

  loading = false;
  constructor(private fb: FormBuilder, private req: HttpReqHandlerService, private auth: AuthService) {

  }
 fileForm = this.fb.group({
    fileInput: [null, Validators.required]
  });

  collapseModalEvent() {
    this.collapseModal.emit(true);
  }


  refetchCurricula() {
    this.refetch.emit(true);
  }

  onFileSelected(event: any) {
    const file = event.target.files[0];
    const validTypes = ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel'];

    if (file && validTypes.includes(file.type)) {
      this.selectedFile = file;
      this.fileForm.patchValue({
        fileInput: file.name // Just set the file name to the form control, not the file object itself
      });
    } else {
      alert('Please select a valid Excel file.');
    }
  }
  downloadFile(){
    this.req.getResource('sample-csv-curricula', { responseType: 'blob' as 'json' })
    .subscribe({
      next: (data: any) => {
        saveAs(data, 'sample-curriculum.xlsx');
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
      this.loading = true;
      this.req.postResource('curriculum/bulk', formData, httpOptions(this.auth.getCookie('user'), true))
        .subscribe({
          next: (data: any) => {
            this.collapseModalEvent();
            this.refetchCurricula();
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
