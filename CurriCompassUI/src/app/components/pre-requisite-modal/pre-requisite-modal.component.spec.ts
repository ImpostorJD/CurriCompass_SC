import { ComponentFixture, TestBed } from '@angular/core/testing';

import { PreRequisiteModalComponent } from './pre-requisite-modal.component';

describe('PreRequisiteModalComponent', () => {
  let component: PreRequisiteModalComponent;
  let fixture: ComponentFixture<PreRequisiteModalComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [PreRequisiteModalComponent]
    })
    .compileComponents();
    
    fixture = TestBed.createComponent(PreRequisiteModalComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
