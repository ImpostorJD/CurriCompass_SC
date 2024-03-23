import { ComponentFixture, TestBed } from '@angular/core/testing';

import { StudentRecordManagementComponent } from './student-record-management.component';

describe('StudentRecordManagementComponent', () => {
  let component: StudentRecordManagementComponent;
  let fixture: ComponentFixture<StudentRecordManagementComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [StudentRecordManagementComponent]
    })
    .compileComponents();
    
    fixture = TestBed.createComponent(StudentRecordManagementComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
