import { CommonModule } from '@angular/common';
import { Component } from '@angular/core';

@Component({
  selector: 'app-student-record-management',
  standalone: true,
  imports: [CommonModule],
  templateUrl: './student-record-management.component.html',
  styleUrl: './student-record-management.component.css'
})
export class StudentRecordManagementComponent {
  iterates: Array<number> = Array.from({ length: 20 }, (_, i) => i + 1);
}
