import { InputContainer } from './styles';

const Input = ({value}) => {
    return (
      <InputContainer>
        <Input disabled value={value} />
      </InputContainer>
    );
  }
  
  export default Input;
  