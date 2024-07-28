import { ComponentFixture, TestBed } from '@angular/core/testing';

import { PendingRegistrationStudentComponent } from './pending-registration-student.component';

describe('PendingRegistrationStudentComponent', () => {
  let component: PendingRegistrationStudentComponent;
  let fixture: ComponentFixture<PendingRegistrationStudentComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [PendingRegistrationStudentComponent]
    })
    .compileComponents();
    
    fixture = TestBed.createComponent(PendingRegistrationStudentComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
