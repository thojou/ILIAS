<?php

class SkillAssignments
{
    public function __construct(
        private readonly array $question,
        /** @var array<ilAssQuestionSkillAssignment> */
        private readonly array $skill_assignments
    ) {
    }

    public function getQuestion(): array
    {
        return $this->question;
    }

    /**
     * @return array<ilAssQuestionSkillAssignment>
     */
    public function getSkillAssignments(): array
    {
        return $this->skill_assignments;
    }
}