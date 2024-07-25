import { ComponentFixture, TestBed } from '@angular/core/testing';

import { StudentBulkRegistrationModalComponent } from './student-bulk-registration-modal.component';

describe('StudentBulkRegistrationModalComponent', () => {
  let component: StudentBulkRegistrationModalComponent;
  let fixture: ComponentFixture<StudentBulkRegistrationModalComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [StudentBulkRegistrationModalComponent]
    })
    .compileComponents();
    
    fixture = TestBed.createComponent(StudentBulkRegistrationModalComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
