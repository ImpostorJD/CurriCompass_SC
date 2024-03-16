import { ComponentFixture, TestBed } from '@angular/core/testing';

import { EditCurriculumComponent } from './edit-curriculum.component';

describe('EditCurriculumComponent', () => {
  let component: EditCurriculumComponent;
  let fixture: ComponentFixture<EditCurriculumComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [EditCurriculumComponent]
    })
    .compileComponents();
    
    fixture = TestBed.createComponent(EditCurriculumComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
